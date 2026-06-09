#!/bin/bash

# GrowDev Vercel Full-Stack Deployment Script
# Deploys Laravel backend + Vite frontend as a single Vercel project
#
# Prerequisites:
# - Vercel CLI installed: npm install -g vercel
# - Logged in to Vercel: vercel login
# - PHP 8.2+ with Composer installed locally

set -e

echo "======================================"
echo "  GrowDev Vercel Full-Stack Deploy"
echo "======================================"
echo ""

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

VERCEL_PROJECT_NAME="growdev"
VERCEL_ENV=${1:-production}

echo -e "${BLUE}Configuration:${NC}"
echo "  Project Name: $VERCEL_PROJECT_NAME"
echo "  Environment: $VERCEL_ENV"
echo ""

# Step 1: Verify Vercel CLI
echo -e "${BLUE}[1/8]${NC} Checking Vercel CLI..."
if ! command -v vercel &> /dev/null; then
    echo -e "${RED}✗ Vercel CLI not found. Install with: npm install -g vercel${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Vercel CLI installed${NC}"

# Step 2: Verify logged in
echo -e "${BLUE}[2/8]${NC} Checking Vercel authentication..."
if ! vercel whoami &> /dev/null; then
    echo -e "${YELLOW}⚠ Not logged in. Running login...${NC}"
    vercel login
fi
VERCEL_USER=$(vercel whoami)
echo -e "${GREEN}✓ Logged in as: $VERCEL_USER${NC}"

# Step 3: Install Composer dependencies
echo -e "${BLUE}[3/8]${NC} Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader
    echo -e "${GREEN}✓ Composer dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠ Composer not found locally. Will be handled by Vercel build.${NC}"
fi

# Step 4: Install Node dependencies
echo -e "${BLUE}[4/8]${NC} Installing Node dependencies..."
if [ -d "node_modules" ]; then
    echo "  node_modules exists, running npm install to ensure up-to-date..."
fi
npm install
echo -e "${GREEN}✓ Node dependencies installed${NC}"

# Step 5: Build frontend
echo -e "${BLUE}[5/8]${NC} Building frontend assets..."
npm run build
if [ -d "public/build" ]; then
    echo -e "${GREEN}✓ Build successful${NC}"
    echo "  Output: public/build/"
else
    echo -e "${RED}✗ Build failed${NC}"
    exit 1
fi

# Step 6: Link Vercel project
echo -e "${BLUE}[6/8]${NC} Linking Vercel project..."
if [ -f ".vercel/project.json" ]; then
    echo "  Project already linked"
else
    vercel link --project-name="$VERCEL_PROJECT_NAME"
fi
echo -e "${GREEN}✓ Project linked${NC}"

# Step 7: Set environment variables
echo -e "${BLUE}[7/8]${NC} Setting environment variables..."
echo "  Set environment variables via Vercel dashboard or:"
echo "  vercel env add APP_KEY --environment=production"
echo "  vercel env add DB_CONNECTION --environment=production"
echo "  vercel env add DB_HOST --environment=production"
echo "  vercel env add DB_PORT --environment=production"
echo "  vercel env add DB_DATABASE --environment=production"
echo "  vercel env add DB_USERNAME --environment=production"
echo "  vercel env add DB_PASSWORD --environment=production"
echo "  vercel env add OPENROUTER_API_KEY --environment=production"
echo "  vercel env add OPENROUTER_MODEL --environment=production"
echo -e "${GREEN}✓ Environment variables listed${NC}"

# Step 8: Deploy
echo -e "${BLUE}[8/8]${NC} Deploying to Vercel ($VERCEL_ENV)..."
if [ "$VERCEL_ENV" = "production" ]; then
    vercel --prod
else
    vercel
fi

echo ""
echo -e "${GREEN}✓ Deployment complete!${NC}"
echo ""
echo "  Dashboard: https://vercel.com/$VERCEL_USER/$VERCEL_PROJECT_NAME"
echo "  Logs: vercel logs"
echo ""
echo "  Post-deploy steps:"
echo "  1. Run migrations: vercel run php artisan migrate --force"
echo "     (or via GitHub Actions)"
echo "  2. Verify site loads in browser"
echo "  3. Monitor logs: vercel logs"
