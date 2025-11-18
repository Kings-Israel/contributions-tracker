#!/bin/bash

set -e  # Exit on error

STACK_NAME="contributions-tracker-stack"

# Create or update the CloudFormation stack
aws cloudformation deploy \
  --stack-name $STACK_NAME \
  --template-file stack.yml \
  --capabilities CAPABILITY_IAM CAPABILITY_NAMED_IAM \
  --no-fail-on-empty-changeset

# Wait for the stack to be ready
aws cloudformation wait stack-exists --stack-name $STACK_NAME
aws cloudformation wait stack-create-complete --stack-name $STACK_NAME || aws cloudformation wait stack-update-complete --stack-name $STACK_NAME

# Get the EKS cluster name, RDS endpoint, Redis endpoint, and S3 bucket name from outputs
CLUSTER_NAME=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='ClusterName'].OutputValue" --output text)
RDS_ENDPOINT=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='RDSEndpoint'].OutputValue" --output text)
REDIS_ENDPOINT=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='RedisEndpoint'].OutputValue" --output text)
S3_BUCKET=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='S3BucketName'].OutputValue" --output text)
ECR_URI=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].Outputs[?OutputKey=='ECRRepositoryUri'].OutputValue" --output text)

# Update kubeconfig to connect to the EKS cluster
aws eks update-kubeconfig --name $CLUSTER_NAME

# Update deployment.yaml with the RDS endpoint, Redis endpoint, and S3 bucket name
sed -i "s/value: !GetAtt RDSInstance.Endpoint.Address/value: $RDS_ENDPOINT/" deployment.yml
sed -i "s/value: !GetAtt RedisCluster.RedisEndpoint.Address/value: $REDIS_ENDPOINT/" deployment.yml
sed -i "s/value: !Ref S3Bucket/value: $S3_BUCKET/" deployment.yml
sed -i "s|image: !GetAtt ECRRepository.RepositoryUri:latest|image: $ECR_URI:latest|" deployment.yml

# Deploy the Kubernetes resources
kubectl apply -f deployment.yml
kubectl apply -f service.yml

# Get the Load Balancer URL
echo "Waiting for Load Balancer to be provisioned..."
sleep 60  # Wait for ELB to be ready
LB_URL=$(kubectl get svc contributions-tracker-service -o jsonpath='{.status.loadBalancer.ingress[0].hostname}')
echo "Laravel application is available at: http://$LB_URL"
echo "RDS Endpoint: $RDS_ENDPOINT"
echo "Redis Endpoint: $REDIS_ENDPOINT"
echo "S3 Bucket: $S3_BUCKET"
echo "ECR Repository URI: $ECR_URI"
