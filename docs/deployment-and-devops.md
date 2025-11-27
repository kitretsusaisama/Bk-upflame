# Deployment & DevOps

> **Production deployment and infrastructure guide**

## Table of Contents

- [Requirements](#requirements)
- [Server Setup](#server-setup)
- [Deployment Process](#deployment-process)
- [Configuration](#configuration)
- [Process Management](#process-management)
- [Monitoring](#monitoring)
- [Backup & Recovery](#backup--recovery)

## Requirements

### Production Server

**Minimum:**
- Ubuntu 22.04 LTS or similar
- PHP 8.2+ with extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Redis 6+ (recommended)
- Nginx or Apache
- Supervisor (for queue workers)

**Recommended:**
- 2+ CPU cores
- 4GB+ RAM
- 40GB+ SSD storage
- HTTPS certificate (Let's Encrypt)

## Server Setup

### 1. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php
sudo apt install php8.2-fpm php8.2-mysql php8.2-redis php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL
sudo apt install mysql-server

# Install Redis
sudo apt install redis-server

# Install Nginx
sudo apt install nginx

# Install Supervisor
sudo apt install supervisor
```

### 2. Configure MySQL

```bash
sudo mysql_secure_installation

# Create database
sudo mysql
CREATE DATABASE laravel_production;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON laravel_production.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Configure Nginx

```nginx
# /etc/nginx/sites-available/laravel

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laravel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Deployment Process

### Initial Deploy

```bash
# 1. Clone repository
cd /var/www
git clone https://github.com/yourorg/yourrepo.git laravel
cd laravel

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# Edit .env with production settings
nano .env

# 4. Run migrations
php artisan migrate --force

# 5. Seed production data
php artisan db:seed --class=ProductionSeeder

# 6. Build assets
npm run build

# 7. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Set permissions
sudo chown -R www-data:www-data /var/www/laravel
sudo chmod -R 755 /var/www/laravel
sudo chmod -R 775 /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/bootstrap/cache
```

### Production `.env`

```env
APP_NAME="Enterprise SaaS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_production
DB_USERNAME=laravel_user
DB_PASSWORD=strong_password

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

### Continuous Deployment

```bash
#!/bin/bash
# deploy.sh

cd /var/www/laravel

# Enable maintenance mode
php artisan down

# Pull latest code
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build

# Restart queue workers
sudo supervisorctl restart laravel-worker:*

# Disable maintenance mode
php artisan up

echo "Deployment complete!"
```

## Process Management

### Queue Workers (Supervisor)

```ini
# /etc/supervisor/conf.d/laravel-worker.conf

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Apply configuration
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*

# Check status
sudo supervisorctl status laravel-worker:*
```

## Monitoring

### Laravel Telescope (Development Only)

```bash
composer require --dev laravel/telescope
php artisan telescope:install
php artisan migrate
```

**Disable in production** or protect with authentication.

### Application Monitoring

**Tools:**
- **New Relic** - APM monitoring
- **Sentry** - Error tracking
- **Laravel Log Viewer** - Log management

### Server Monitoring

```bash
# Install monitoring tools
sudo apt install htop iotop nethogs
```

**External Services:**
- **UptimeRobot** - Uptime monitoring
- **Datadog** - Comprehensive monitoring
- **AWS CloudWatch** - If on AWS

## Backup & Recovery

### Database Backups

```bash
# /usr/local/bin/backup-db.sh

#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/mysql"

mkdir -p $BACKUP_DIR

mysqldump -u laravel_user -p'password' laravel_production | gzip > "$BACKUP_DIR/backup_$DATE.sql.gz"

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

**Cron job:**
```bash
# Daily at 2 AM
0 2 * * * /usr/local/bin/backup-db.sh
```

### File Backups

```bash
# Backup uploads and storage
tar -czf /var/backups/laravel_$(date +%Y%m%d).tar.gz /var/www/laravel/storage
```

### Automated S3 Backups

```bash
# Install AWS CLI
sudo apt install awscli

# Configure credentials
aws configure

# Sync to S3
aws s3 sync /var/backups/ s3://your-bucket/backups/
```

## Cross-Links

- [Configuration](config-and-env.md) - Environment variables
- [Security](security.md) - Production security
- [Performance](future-extensions.md#scalability-enhancements) - Optimization techniques
