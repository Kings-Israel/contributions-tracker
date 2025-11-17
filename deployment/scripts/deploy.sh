#!/bin/bash

set -e

# Configuration
STACK_NAME="contributions-tracker-infrastructure"
CLUSTER_NAME="contributions-tracker-cluster"
AWS_REGION="us-east-1"

echo "🚀 Starting deployment process..."

# Deploy infrastructure
echo "📦 Deploying CloudFormation stack..."
aws cloudformation deploy \
    --template-file stack.yml \
    --stack-name $STACK_NAME \
    --capabilities CAPABILITY_IAM \
    --parameter-overrides ClusterName=$CLUSTER_NAME \
    --region $AWS_REGION

aws cloudformation wait stack-exists --stack-name $STACK_NAME
aws cloudformation wait stack-create-complete --stack-name $STACK_NAME || aws cloudformation wait stack-update-complete --stack-name $STACK_NAME

echo "✅ Infrastructure deployed successfully!"

# Update kubeconfig
echo "🔧 Updating kubeconfig..."
aws eks update-kubeconfig --region $AWS_REGION --name $CLUSTER_NAME

# Install necessary controllers
echo "🎛️ Installing AWS Load Balancer Controller..."
kubectl apply -k "github.com/aws/eks-charts/stable/aws-load-balancer-controller/crds?ref=master"

# Install Secrets Store CSI Driver
echo "🔐 Installing Secrets Store CSI Driver..."
helm repo add secrets-store-csi-driver https://kubernetes-sigs.github.io/secrets-store-csi-driver/charts
helm repo update
helm install c
