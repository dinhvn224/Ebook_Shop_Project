#!/bin/bash

# Chatbot AI Gemini Stop Script
# This script stops all chatbot services

echo "🛑 Stopping Chatbot AI Gemini Services..."
echo "========================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to kill process by PID
kill_process() {
    local pid=$1
    local name=$2

    if [ -n "$pid" ] && kill -0 $pid 2>/dev/null; then
        echo -e "${BLUE}🛑 Stopping $name (PID: $pid)...${NC}"
        kill $pid
        sleep 2

        # Force kill if still running
        if kill -0 $pid 2>/dev/null; then
            echo -e "${YELLOW}⚠️  Force killing $name...${NC}"
            kill -9 $pid
        fi

        echo -e "${GREEN}✅ $name stopped${NC}"
    else
        echo -e "${YELLOW}⚠️  $name is not running${NC}"
    fi
}

# Stop Laravel server
if [ -f storage/laravel.pid ]; then
    LARAVEL_PID=$(cat storage/laravel.pid)
    kill_process $LARAVEL_PID "Laravel server"
    rm -f storage/laravel.pid
else
    echo -e "${YELLOW}⚠️  Laravel PID file not found${NC}"
    # Try to kill by port
    LARAVEL_PID=$(lsof -ti:8000 2>/dev/null)
    if [ -n "$LARAVEL_PID" ]; then
        kill_process $LARAVEL_PID "Laravel server (by port)"
    fi
fi

# Stop n8n server
if [ -f storage/n8n.pid ]; then
    N8N_PID=$(cat storage/n8n.pid)
    kill_process $N8N_PID "n8n server"
    rm -f storage/n8n.pid
else
    echo -e "${YELLOW}⚠️  n8n PID file not found${NC}"
    # Try to kill by port
    N8N_PID=$(lsof -ti:5678 2>/dev/null)
    if [ -n "$N8N_PID" ]; then
        kill_process $N8N_PID "n8n server (by port)"
    fi
fi

# Clean up PID files
rm -f storage/chatbot.pids
rm -f storage/laravel.pid
rm -f storage/n8n.pid

# Check if processes are still running
echo -e "${BLUE}🔍 Checking if services are stopped...${NC}"

if lsof -i:8000 >/dev/null 2>&1; then
    echo -e "${RED}❌ Laravel server is still running on port 8000${NC}"
else
    echo -e "${GREEN}✅ Laravel server stopped${NC}"
fi

if lsof -i:5678 >/dev/null 2>&1; then
    echo -e "${RED}❌ n8n server is still running on port 5678${NC}"
else
    echo -e "${GREEN}✅ n8n server stopped${NC}"
fi

echo ""
echo -e "${GREEN}🎉 All chatbot services stopped!${NC}"
echo "========================================"
echo ""
echo -e "${YELLOW}📊 Log files are still available at:${NC}"
echo "Laravel: storage/logs/laravel-server.log"
echo "n8n: storage/logs/n8n-server.log"
echo ""
echo -e "${YELLOW}🚀 To restart services:${NC}"
echo "Run: ./start-chatbot.sh"
