#!/bin/bash

# GrowDev Vercel Frontend Deployment Script
# This script automates the deployment process using Vercel CLI
# 
# Prerequisites:
# - Vercel CLI installed: npm install -g vercel
# - Logged in to Vercel: vercel login
# - Node.js 18+ installed
# - All environment variables prepared

set -e

echo "🚀 GrowDev Vercel Frontend Deployment"
echo "======================================"
echo ""

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration - UPDATE THESE VALUES
VERCEL_PROJECT_NAME="growdev"
BACKEND_API_URL="${VITE_API_URL:-https://api.yourdomain.com}"
if [ -z "${VITE_OPENROUTER_API_KEY:-}" ]; then
    echo "✗ ERROR: VITE_OPENROUTER_API_KEY not set. Set it as an environment variable."
    exit 1
fi
OPENROUTER_API_KEY="$VITE_OPENROUTER_API_KEY"
OPENROUTER_MODEL="${VITE_OPENROUTER_MODEL:-openai/gpt-3.5-turbo}"
VERCEL_ENV=${1:-production}

echo -e "${BLUE}Configuration:${NC}"
echo "  Project Name: $VERCEL_PROJECT_NAME"
echo "  Backend URL: $BACKEND_API_URL"
echo "  OpenRouter Model: $OPENROUTER_MODEL"
echo "  Environment: $VERCEL_ENV"
echo "  OpenRouter API Key: ${OPENROUTER_API_KEY:0:10}****"
echo ""

# Step 1: Verify Vercel CLI is installed
echo -e "${BLUE}[1/6]${NC} Checking Vercel CLI..."
if ! command -v vercel &> /dev/null; then
    echo -e "${RED}✗ Vercel CLI not found. Install with: npm install -g vercel${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Vercel CLI installed${NC}"
echo ""

# Step 2: Verify logged in
echo -e "${BLUE}[2/6]${NC} Checking Vercel authentication..."
if ! vercel whoami &> /dev/null; then
    echo -e "${YELLOW}⚠ Not logged in to Vercel. Running login...${NC}"
    vercel login
fi
VERCEL_USER=$(vercel whoami)
echo -e "${GREEN}✓ Logged in as: $VERCEL_USER${NC}"
echo ""

# Step 3: Install Node dependencies
echo -e "${BLUE}[3/6]${NC} Installing Node dependencies..."
if [ -d "node_modules" ]; then
    echo "  Node modules already installed, skipping..."
else
    npm install
fi
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

# Step 4: Build frontend
echo -e "${BLUE}[4/6]${NC} Building frontend (npm run build)..."
npm run build
if [ -d "public/build" ]; then
    echo -e "${GREEN}✓ Build successful${NC}"
    echo "  Output directory: public/build/"
    echo "  Files: $(find public/build -type f | wc -l) files"
else
    echo -e "${RED}✗ Build failed - public/build directory not found${NC}"
    exit 1
fi
echo ""

# Step 5: Link/Create Vercel project
echo -e "${BLUE}[5/6]${NC} Linking Vercel project..."
if [ -f ".vercel/project.json" ]; then
    echo "  Project already linked, skipping..."
else
    echo "  Running: vercel link --project-name=$VERCEL_PROJECT_NAME"
    vercel link --project-name="$VERCEL_PROJECT_NAME"
fi
echo -e "${GREEN}✓ Project linked${NC}"
echo ""

# Step 6: Set environment variables in Vercel
echo -e "${BLUE}[6/6]${NC} Setting environment variables in Vercel..."
echo "  Setting VITE_API_URL..."
vercel env add VITE_API_URL --environment=production <<< "$BACKEND_API_URL" > /dev/null || echo "  Variable may already exist"

echo "  Setting VITE_OPENROUTER_API_KEY..."
vercel env add VITE_OPENROUTER_API_KEY --environment=production <<< "$OPENROUTER_API_KEY" > /dev/null || echo "  Variable may already exist"

echo "  Setting VITE_OPENROUTER_MODEL..."
vercel env add VITE_OPENROUTER_MODEL --environment=production <<< "$OPENROUTER_MODEL" > /dev/null || echo "  Variable may already exist"

echo -e "${GREEN}✓ Environment variables configured${NC}"
echo ""

# Step 7: Deploy
echo -e "${BLUE}[7/7]${NC} Deploying to Vercel ($VERCEL_ENV)..."
if [ "$VERCEL_ENV" = "production" ]; then
    echo "  Running: vercel --prod"
    vercel --prod
    DEPLOYMENT_URL=$(vercel ls | head -n 3 | tail -n 1 | awk '{print $2}')
else
    echo "  Running: vercel (preview deployment)"
    vercel
    DEPLOYMENT_URL=$(vercel ls | head -n 3 | tail -n 1 | awk '{print $2}')
fi
echo ""

# Success message
echo -e "${GREEN}✓ Deployment successful!${NC}"
echo ""
echo "📊 Deployment Summary:"
echo "  Project: $VERCEL_PROJECT_NAME"
echo "  Environment: $VERCEL_ENV"
echo "  Backend API: $BACKEND_API_URL"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "  1. Visit your deployment: https://$DEPLOYMENT_URL"
echo "  2. Test API connectivity (check browser console)"
echo "  3. Test authentication (login/register)"
echo "  4. Monitor deployment logs: vercel logs"
echo ""
echo "📝 View deployment:"
echo "  Dashboard: https://vercel.com/$VERCEL_USER/$VERCEL_PROJECT_NAME"
echo "  Logs: vercel logs"
echo "  Rebuild: vercel --prod"
echo ""
