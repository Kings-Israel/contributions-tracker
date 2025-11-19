#!/bin/bash
set -e

STACK_NAME="contributions-tracker"
ACCOUNTID=$(aws sts get-caller-identity --query Account --output text)
AWS_DEFAULT_REGION=$(aws configure get region)

echo "Deploying infrastructure..."
aws cloudformation deploy \
  --stack-name $STACK_NAME \
  --template-file stack.yml \
  --capabilities CAPABILITY_IAM CAPABILITY_NAMED_IAM \
  --no-fail-on-empty-changeset

echo "Waiting for stack..."
aws cloudformation wait stack-create-complete --stack-name $STACK_NAME || true

# Get outputs
CLUSTER_NAME=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='ClusterName'].OutputValue" --output text)
RDS_ENDPOINT=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='RDSEndpoint'].OutputValue" --output text)
REDIS_ENDPOINT=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='RedisEndpoint'].OutputValue" --output text)
S3_BUCKET=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='S3BucketName'].OutputValue" --output text)
ECR_URI=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='ECRRepositoryUri'].OutputValue" --output text)
LB_ROLE_ARN=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='LoadBalancerControllerRoleArn'].OutputValue" --output text)

echo "Updating kubeconfig..."
aws eks update-kubeconfig --name $CLUSTER_NAME

# --- INSTALL AWS LOAD BALANCER CONTROLLER ---
echo "Installing AWS Load Balancer Controller..."

# Create IAM policy
curl -o iam-policy.json https://raw.githubusercontent.com/kubernetes-sigs/aws-load-balancer-controller/main/docs/install/iam_policy.json
aws iam create-policy --policy-name AWSLoadBalancerControllerIAMPolicy --policy-document file://iam-policy.json || true

# Associate IAM role with service account
eksctl create iamserviceaccount \
  --cluster=$CLUSTER_NAME \
  --namespace=kube-system \
  --name=aws-load-balancer-controller \
  --role-name ContributionsTrackerEKSALBControllerRole \
  --attach-policy-arn=arn:aws:iam::$ACCOUNTID:policy/AWSLoadBalancerControllerIAMPolicy \
  --approve --override-existing-serviceaccounts

# Install via Helm
helm repo add eks https://aws.github.io/eks-charts
helm repo update

helm upgrade -i aws-load-balancer-controller eks/aws-load-balancer-controller \
  -n kube-system \
  --set clusterName=$CLUSTER_NAME \
  --set serviceAccount.create=false \
  --set serviceAccount.name=aws-load-balancer-controller \
  --set region=$AWS_DEFAULT_REGION \
  --set vpcId=$(aws ec2 describe-vpcs --filters Name=tag:Name,Values=contributions-tracker-eks --query "Vpcs[0].VpcId" --output text)

echo "Waiting for ALB Controller to be ready..."
kubectl wait --for=condition=Available --timeout=300s deployment/aws-load-balancer-controller -n kube-system

# --- APPLY K8s MANIFESTS ---
echo "Creating secret..."
kubectl create secret generic contributions-tracker-secrets \
  --from-literal=db-password=SecurePassword123 \
  --dry-run=client -o yml | kubectl apply -f -

echo "Updating deployment.yml..."
sed -i "s|placeholder-ecr-uri:latest|$ECR_URI:latest|" deployment.yml
sed -i "s|your-rds-endpoint-here|$RDS_ENDPOINT|" deployment.yml
sed -i "s|your-redis-endpoint-here|$REDIS_ENDPOINT|" deployment.yml
sed -i "s|your-s3-bucket-name|$S3_BUCKET|" deployment.yml

kubectl apply -f namespace.yml
kubectl apply -f deployment.yml
kubectl apply -f service.yml
kubectl apply -f ingress.yml

echo "Waiting for Ingress to get an address..."
sleep 60
INGRESS_HOST=$(kubectl get ingress contributions-tracker-ingress -o jsonpath='{.status.loadBalancer.ingress[0].hostname}')

echo ""
echo "App URL: http://$INGRESS_HOST"
echo ""
echo "GitHub Secrets:"
echo "  AWS_ACCESS_KEY_ID"
echo "  AWS_SECRET_ACCESS_KEY"
echo "  AWS_REGION: $(aws configure get region)"
echo "  EKS_CLUSTER_NAME: $CLUSTER_NAME"
echo "  ECR_REPOSITORY: ${ECR_URI##*/}"
