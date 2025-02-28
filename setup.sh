#!/bin/bash

# Family Recipe Book Plugin Setup Script

echo "Setting up Family Recipe Book Plugin development environment..."

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "Error: npm is not installed. Please install Node.js and npm first."
    exit 1
fi

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "Error: Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Docker is installed and running
if ! command -v docker &> /dev/null; then
    echo "Error: Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker is running
if ! docker info &> /dev/null; then
    echo "Error: Docker is not running. Please start Docker first."
    exit 1
fi

echo "Installing npm dependencies..."
npm install

echo "Installing Composer dependencies..."
composer install

echo "Building assets..."
npm run build

echo "Starting WordPress environment..."
npm run env:start

echo "Setup complete! Your local development environment is ready."
echo ""
echo "WordPress is available at: http://localhost:8888"
echo "Admin username: admin"
echo "Admin password: password"
echo ""
echo "To start development with live reloading, run: npm run dev"
echo "To stop the WordPress environment, run: npm run env:stop"
echo ""
echo "Happy coding!"