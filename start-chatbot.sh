#!/bin/bash

# Chatbot AI Gemini Startup Script
# This script starts all necessary services for the chatbot

echo "🤖 Starting Chatbot AI Gemini Services..."
echo "========================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check if port is in use
port_in_use() {
    lsof -i :$1 >/dev/null 2>&1
}

# Function to wait for service
wait_for_service() {
    local host=$1
    local port=$2
    local service_name=$3

    echo -e "${YELLOW}⏳ Waiting for $service_name to be ready...${NC}"

    for i in {1..30}; do
        if nc -z $host $port 2>/dev/null; then
            echo -e "${GREEN}✅ $service_name is ready!${NC}"
            return 0
        fi
        sleep 1
    done

    echo -e "${RED}❌ $service_name failed to start${NC}"
    return 1
}

# Check prerequisites
echo -e "${BLUE}🔍 Checking prerequisites...${NC}"

if ! command_exists php; then
    echo -e "${RED}❌ PHP is not installed${NC}"
    exit 1
fi

if ! command_exists composer; then
    echo -e "${RED}❌ Composer is not installed${NC}"
    exit 1
fi

if ! command_exists node; then
    echo -e "${RED}❌ Node.js is not installed${NC}"
    exit 1
fi

if ! command_exists npm; then
    echo -e "${RED}❌ npm is not installed${NC}"
    exit 1
fi

echo -e "${GREEN}✅ All prerequisites are installed${NC}"

# Check if .env file exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  .env file not found. Creating from example...${NC}"
    if [ -f env-chatbot.example ]; then
        cp env-chatbot.example .env
        echo -e "${YELLOW}⚠️  Please update .env file with your configuration${NC}"
    else
        echo -e "${RED}❌ env-chatbot.example not found${NC}"
        exit 1
    fi
fi

# Install Laravel dependencies
echo -e "${BLUE}📦 Installing Laravel dependencies...${NC}"
if [ -f composer.json ]; then
    composer install --no-interaction
    echo -e "${GREEN}✅ Laravel dependencies installed${NC}"
else
    echo -e "${RED}❌ composer.json not found${NC}"
    exit 1
fi

# Install Node.js dependencies
echo -e "${BLUE}📦 Installing Node.js dependencies...${NC}"
if [ -f package.json ]; then
    npm install
    echo -e "${GREEN}✅ Node.js dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠️  package.json not found, skipping npm install${NC}"
fi

# Generate Laravel key if not exists
echo -e "${BLUE}🔑 Generating Laravel application key...${NC}"
php artisan key:generate --force

# Run migrations
echo -e "${BLUE}🗄️  Running database migrations...${NC}"
php artisan migrate --force

# Clear caches
echo -e "${BLUE}🧹 Clearing Laravel caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Check if n8n is installed
if ! command_exists n8n; then
    echo -e "${YELLOW}⚠️  n8n is not installed. Installing...${NC}"
    npm install n8n -g
fi

# Start services in background
echo -e "${BLUE}🚀 Starting services...${NC}"

# Start Laravel server
if ! port_in_use 8000; then
    echo -e "${BLUE}🌐 Starting Laravel server on port 8000...${NC}"
    php artisan serve --host=0.0.0.0 --port=8000 > storage/logs/laravel-server.log 2>&1 &
    LARAVEL_PID=$!
    echo $LARAVEL_PID > storage/laravel.pid
    echo -e "${GREEN}✅ Laravel server started (PID: $LARAVEL_PID)${NC}"
else
    echo -e "${YELLOW}⚠️  Laravel server already running on port 8000${NC}"
fi

# Start n8n server
if ! port_in_use 5678; then
    echo -e "${BLUE}⚙️  Starting n8n server on port 5678...${NC}"
    n8n start > storage/logs/n8n-server.log 2>&1 &
    N8N_PID=$!
    echo $N8N_PID > storage/n8n.pid
    echo -e "${GREEN}✅ n8n server started (PID: $N8N_PID)${NC}"
else
    echo -e "${YELLOW}⚠️  n8n server already running on port 5678${NC}"
fi

# Wait for services to be ready
echo -e "${BLUE}⏳ Waiting for services to be ready...${NC}"

if wait_for_service localhost 8000 "Laravel"; then
    echo -e "${GREEN}✅ Laravel is ready at http://localhost:8000${NC}"
else
    echo -e "${RED}❌ Laravel failed to start${NC}"
fi

if wait_for_service localhost 5678 "n8n"; then
    echo -e "${GREEN}✅ n8n is ready at http://localhost:5678${NC}"
else
    echo -e "${RED}❌ n8n failed to start${NC}"
fi

# Display status
echo ""
echo -e "${GREEN}🎉 Chatbot AI Gemini is ready!${NC}"
echo "========================================"
echo -e "${BLUE}📚 Laravel:${NC} http://localhost:8000"
echo -e "${BLUE}⚙️  n8n:${NC} http://localhost:5678"
echo -e "${BLUE}🤖 Chatbot:${NC} Available on all pages"
echo ""
echo -e "${YELLOW}📝 Next steps:${NC}"
echo "1. Import n8n workflow from n8n-workflow-chatbot.json"
echo "2. Configure Gemini API key in n8n environment variables"
echo "3. Test chatbot on any page"
echo ""
echo -e "${YELLOW}🛑 To stop services:${NC}"
echo "Run: ./stop-chatbot.sh"
echo ""
echo -e "${YELLOW}📊 To view logs:${NC}"
echo "Laravel: tail -f storage/logs/laravel-server.log"
echo "n8n: tail -f storage/logs/n8n-server.log"
echo ""

# Save PIDs for later use
echo "LARAVEL_PID=$LARAVEL_PID" > storage/chatbot.pids
echo "N8N_PID=$N8N_PID" >> storage/chatbot.pids

echo -e "${GREEN}✅ Startup complete!${NC}"
