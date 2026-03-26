#!/bin/bash
set -e

PROJECT_DIR="/var/www/isp-jabbar"

echo "🚀 Deploying ISP Jabbar to $PROJECT_DIR..."

# Check if directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Error: Directory $PROJECT_DIR does not exist."
    exit 1
fi

# Navigate to project dir
cd "$PROJECT_DIR"

# Enable maintenance mode
echo "🔒 Enabling maintenance mode..."
php artisan down || true

# Pull latest code
echo "📡 Pulling latest code..."
git pull origin main

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Run migrations
echo "🗄️  Migrating database..."
php artisan migrate --force

# Clear and cache config
echo "🧹 Optimizing configuration..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets (if node is available)
if command -v npm &> /dev/null; then
    echo "🎨 Building frontend assets..."
    npm ci
    npm run build
else
    echo "⚠️  Node.js not found, skipping asset build. Ensure build assets are committed or Node is installed."
fi

# Exit maintenance mode
echo "🔓 Disabling maintenance mode..."
php artisan up

echo "✅ Deployment finished successfully!"
echo "⚠️  REMINDER: Please manually update your .env file for security fixes!"
