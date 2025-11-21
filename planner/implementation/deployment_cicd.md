# Deployment and CI/CD Pipeline Setup

## Overview

This document provides comprehensive guidelines for setting up deployment and CI/CD pipelines for the enterprise-grade multi-tenant backend platform. The setup includes automated testing, security scanning, deployment strategies, and rollback procedures.

## CI/CD Architecture

```
Development → GitHub → CI Pipeline → Staging → Production
     ↑                                    ↓
     └────────── Manual Approval ──────────┘
```

## 1. GitHub Actions Workflow

### Main Pipeline Workflow

```yaml
# .github/workflows/ci-cd.yml
name: CI/CD Pipeline

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: test_db
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, pdo, pdo_mysql
        coverage: none
    
    - name: Cache Composer dependencies
      uses: actions/cache@v3
      with:
        path: vendor
        key: composer-${{ hashFiles('composer.lock') }}
    
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress --no-suggest
    
    - name: Run linting
      run: composer lint
    
    - name: Run static analysis
      run: composer analyze
    
    - name: Run unit tests
      run: composer test:unit
      env:
        DB_CONNECTION: mysql
        DB_HOST: 127.0.0.1
        DB_PORT: 3306
        DB_DATABASE: test_db
        DB_USERNAME: root
        DB_PASSWORD: password
    
    - name: Run integration tests
      run: composer test:integration
      env:
        DB_CONNECTION: mysql
        DB_HOST: 127.0.0.1
        DB_PORT: 3306
        DB_DATABASE: test_db
        DB_USERNAME: root
        DB_PASSWORD: password

  security-scan:
    runs-on: ubuntu-latest
    needs: test
    if: github.ref == 'refs/heads/main'
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Run security checks
      run: |
        composer install --prefer-dist --no-progress --no-suggest
        ./vendor/bin/security-checker security:check composer.lock
        ./vendor/bin/phpcs --standard=PSR12 app/
    
    - name: Dependency scanning
      uses: actions/dependency-review-action@v3

  build:
    runs-on: ubuntu-latest
    needs: [test, security-scan]
    if: github.ref == 'refs/heads/main'
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
    
    - name: Install dependencies
      run: |
        composer install --prefer-dist --no-progress --no-suggest --no-dev
        composer dump-autoload --optimize
    
    - name: Create artifact
      run: |
        tar -czf backend-artifact.tar.gz \
            --exclude='*.md' \
            --exclude='.git*' \
            --exclude='tests' \
            --exclude='storage/logs/*' \
            .
    
    - name: Upload artifact
      uses: actions/upload-artifact@v3
      with:
        name: backend-artifact
        path: backend-artifact.tar.gz

  deploy-staging:
    runs-on: ubuntu-latest
    needs: build
    environment: staging
    
    steps:
    - name: Download artifact
      uses: actions/download-artifact@v3
      with:
        name: backend-artifact
    
    - name: Deploy to staging
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.STAGING_HOST }}
        username: ${{ secrets.STAGING_USERNAME }}
        key: ${{ secrets.STAGING_SSH_KEY }}
        script: |
          cd /var/www/staging
          tar -xzf ~/backend-artifact.tar.gz
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          sudo systemctl reload php8.1-fpm
          sudo systemctl reload nginx

  deploy-production:
    runs-on: ubuntu-latest
    needs: deploy-staging
    environment: production
    
    steps:
    - name: Download artifact
      uses: actions/download-artifact@v3
      with:
        name: backend-artifact
    
    - name: Deploy to production
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.PRODUCTION_HOST }}
        username: ${{ secrets.PRODUCTION_USERNAME }}
        key: ${{ secrets.PRODUCTION_SSH_KEY }}
        script: |
          cd /var/www/production
          tar -xzf ~/backend-artifact.tar.gz
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          sudo systemctl reload php8.1-fpm
          sudo systemctl reload nginx
```

## 2. Environment Configuration

### Environment Files Structure

```
.env.example
.env.development
.env.staging
.env.production
```

### Sample Environment File

```env
# Application
APP_NAME="Multi-Tenant Platform"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.upflame.in

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_platform
DB_USERNAME=backend_user
DB_PASSWORD=secure_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Security
JWT_SECRET=base64:your-jwt-secret-here
PASSWORD_RESET_TOKEN_LIFETIME=3600

# Multi-tenancy
TENANT_ROUTE_BY=domain
TENANT_STORAGE_PATH=/var/storage/tenants

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info
LOG_STACK_DRIVERS=stderr,syslog

# Monitoring
SENTRY_LARAVEL_DSN=https://your-sentry-dsn
PROMETHEUS_ENABLED=true
PROMETHEUS_ROUTE=/metrics

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name

# Email
MAIL_MAILER=ses
MAIL_HOST=email-smtp.us-east-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@upflame.in
MAIL_FROM_NAME="Upflame Platform"
```

## 3. Docker Configuration

### Dockerfile

```dockerfile
# Dockerfile
FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libzip-dev \
    mysql-client \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    bcmath \
    ctype \
    json \
    mbstring \
    openssl \
    pdo \
    pdo_mysql \
    tokenizer \
    xml \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --prefer-dist --no-progress --no-dev

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create storage directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

# Set permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Expose port
EXPOSE 80

# Start services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

### Docker Compose for Development

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: backend-app
    ports:
      - "8000:80"
    environment:
      - APP_ENV=local
      - DB_HOST=mysql
      - REDIS_HOST=redis
    volumes:
      - .:/var/www
    depends_on:
      - mysql
      - redis

  mysql:
    image: mysql:8.0
    container_name: backend-mysql
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: backend_platform
      MYSQL_USER: backend_user
      MYSQL_PASSWORD: secure_password
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql

  redis:
    image: redis:alpine
    container_name: backend-redis
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data

  prometheus:
    image: prom/prometheus
    container_name: backend-prometheus
    ports:
      - "9090:9090"
    volumes:
      - ./docker/prometheus/prometheus.yml:/etc/prometheus/prometheus.yml
      - prometheus_data:/prometheus

  grafana:
    image: grafana/grafana
    container_name: backend-grafana
    ports:
      - "3000:3000"
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=admin
    volumes:
      - grafana_data:/var/lib/grafana
    depends_on:
      - prometheus

volumes:
  mysql_data:
  redis_data:
  prometheus_data:
  grafana_data:
```

## 4. Kubernetes Deployment

### Deployment Manifest

```yaml
# k8s/deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: backend-app
  labels:
    app: backend
spec:
  replicas: 3
  selector:
    matchLabels:
      app: backend
  template:
    metadata:
      labels:
        app: backend
    spec:
      containers:
      - name: backend
        image: your-registry/backend-app:latest
        ports:
        - containerPort: 80
        envFrom:
        - configMapRef:
            name: backend-config
        - secretRef:
            name: backend-secrets
        resources:
          requests:
            memory: "256Mi"
            cpu: "250m"
          limits:
            memory: "512Mi"
            cpu: "500m"
        livenessProbe:
          httpGet:
            path: /health
            port: 80
          initialDelaySeconds: 30
          periodSeconds: 10
        readinessProbe:
          httpGet:
            path: /health
            port: 80
          initialDelaySeconds: 5
          periodSeconds: 5
---
apiVersion: v1
kind: Service
metadata:
  name: backend-service
spec:
  selector:
    app: backend
  ports:
    - protocol: TCP
      port: 80
      targetPort: 80
  type: ClusterIP
---
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: backend-ingress
  annotations:
    nginx.ingress.kubernetes.io/rewrite-target: /
spec:
  rules:
  - host: api.upflame.in
    http:
      paths:
      - path: /
        pathType: Prefix
        backend:
          service:
            name: backend-service
            port:
              number: 80
```

### ConfigMap

```yaml
# k8s/configmap.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: backend-config
data:
  APP_NAME: "Multi-Tenant Platform"
  APP_ENV: "production"
  APP_DEBUG: "false"
  LOG_LEVEL: "info"
  DB_CONNECTION: "mysql"
  REDIS_HOST: "redis-service"
  CACHE_DRIVER: "redis"
  SESSION_DRIVER: "redis"
  QUEUE_CONNECTION: "redis"
  PROMETHEUS_ENABLED: "true"
```

### Secret

```yaml
# k8s/secret.yaml
apiVersion: v1
kind: Secret
metadata:
  name: backend-secrets
type: Opaque
data:
  APP_KEY: <base64-encoded-app-key>
  DB_PASSWORD: <base64-encoded-db-password>
  JWT_SECRET: <base64-encoded-jwt-secret>
  AWS_ACCESS_KEY_ID: <base64-encoded-aws-key>
  AWS_SECRET_ACCESS_KEY: <base64-encoded-aws-secret>
```

## 5. Database Migration Strategy

### Migration Commands

```bash
# Generate migration
php artisan make:migration create_tenants_table

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh migrations
php artisan migrate:refresh

# Run migrations for specific environment
php artisan migrate --env=staging
```

### Migration Best Practices

1. **Always backup before migrating in production**
2. **Test migrations in staging first**
3. **Use transactions for complex migrations**
4. **Add proper indexes in migrations**
5. **Document breaking changes**

### Sample Migration with Rollback

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('domain')->unique();
            $table->json('config_json')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('domain');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
```

## 6. Rollback Procedures

### Automated Rollback Script

```bash
#!/bin/bash
# rollback.sh

set -e

ENVIRONMENT=$1
TIMESTAMP=$2

if [ -z "$ENVIRONMENT" ] || [ -z "$TIMESTAMP" ]; then
    echo "Usage: $0 <environment> <timestamp>"
    echo "Example: $0 production 20251120100000"
    exit 1
fi

echo "Rolling back $ENVIRONMENT to $TIMESTAMP"

# Backup current state
echo "Creating backup..."
ssh $ENVIRONMENT "cd /var/www/$ENVIRONMENT && tar -czf backup-$(date +%Y%m%d%H%M%S).tar.gz ."

# Rollback code
echo "Rolling back code..."
ssh $ENVIRONMENT "cd /var/www/$ENVIRONMENT && git checkout $TIMESTAMP"

# Rollback database
echo "Rolling back database..."
ssh $ENVIRONMENT "cd /var/www/$ENVIRONMENT && php artisan migrate:rollback"

# Clear cache
echo "Clearing cache..."
ssh $ENVIRONMENT "cd /var/www/$ENVIRONMENT && php artisan config:clear && php artisan route:clear && php artisan view:clear"

# Restart services
echo "Restarting services..."
ssh $ENVIRONMENT "sudo systemctl reload php8.1-fpm && sudo systemctl reload nginx"

echo "Rollback completed successfully"
```

### Manual Rollback Steps

1. **Identify the issue** that requires rollback
2. **Notify stakeholders** about the rollback
3. **Backup current state** before making changes
4. **Revert code** to previous stable version
5. **Rollback database** if schema changes were involved
6. **Clear caches** and restart services
7. **Verify functionality** after rollback
8. **Document the incident** and lessons learned

## 7. Blue-Green Deployment Strategy

### Deployment Process

```
1. Deploy new version to Green environment
2. Test Green environment thoroughly
3. Switch traffic from Blue to Green
4. Monitor Green environment
5. Decommission Blue environment (now old version)
```

### Traffic Switching Script

```bash
#!/bin/bash
# blue-green-deploy.sh

NEW_ENV=$1
OLD_ENV=$2

# Deploy to new environment
echo "Deploying to $NEW_ENV..."
# Deployment commands here

# Health check new environment
echo "Health checking $NEW_ENV..."
curl -f http://$NEW_ENV/health || exit 1

# Switch traffic
echo "Switching traffic to $NEW_ENV..."
# Load balancer commands here

# Monitor for issues
echo "Monitoring $NEW_ENV..."
# Monitoring commands here

# Decommission old environment after verification period
echo "Scheduling decommission of $OLD_ENV..."
# Decommission commands here
```

## 8. Canary Deployment Strategy

### Gradual Rollout

```
1. Deploy to 5% of users
2. Monitor for issues (1 hour)
3. Increase to 25% if stable
4. Monitor (2 hours)
5. Increase to 100% if stable
6. Complete deployment
```

### Canary Deployment Script

```bash
#!/bin/bash
# canary-deploy.sh

VERSION=$1
PERCENTAGE=$2

echo "Deploying version $VERSION to $PERCENTAGE% of users"

# Update load balancer configuration
kubectl patch deployment backend-app -p \
  "{\"spec\":{\"template\":{\"metadata\":{\"labels\":{\"version\":\"$VERSION\"}}}}}"

# Update service to route percentage of traffic
kubectl patch service backend-service -p \
  "{\"spec\":{\"selector\":{\"version\":[\"current\",\"$VERSION\"],\"canary\":true}}}"

echo "Deployment initiated. Monitor metrics for issues."
```

## 9. Security in CI/CD

### Dependency Scanning

```yaml
# GitHub Actions security scanning
- name: Security audit
  run: |
    composer audit
    npm audit
    pip-audit requirements.txt

- name: Code scanning
  uses: github/codeql-action/analyze@v2
```

### Secrets Management

1. **Never commit secrets** to version control
2. **Use environment variables** for configuration
3. **Rotate secrets regularly**
4. **Encrypt secrets at rest**
5. **Audit secret access**

### Security Checks in Pipeline

```bash
# Security checks script
#!/bin/bash

# Check for hardcoded secrets
grep -r "password\|secret\|key" --exclude-dir=.git --exclude="*.log" . || true

# Check file permissions
find . -name "*.key" -o -name "*.pem" -exec ls -l {} \;

# Run security scanners
./vendor/bin/security-checker security:check composer.lock
./node_modules/.bin/nsp check
```

## 10. Monitoring CI/CD Pipeline

### Pipeline Metrics

1. **Build success rate**
2. **Average build time**
3. **Deployment frequency**
4. **Mean time to recovery**
5. **Change fail rate**

### Pipeline Dashboard

```json
{
  "dashboard": {
    "title": "CI/CD Pipeline Metrics",
    "panels": [
      {
        "title": "Build Success Rate",
        "type": "graph",
        "targets": [
          {
            "expr": "rate(build_success_total[1h]) / rate(build_total[1h])",
            "legendFormat": "Success Rate"
          }
        ]
      },
      {
        "title": "Average Build Time",
        "type": "graph",
        "targets": [
          {
            "expr": "avg(build_duration_seconds)",
            "legendFormat": "Avg Build Time"
          }
        ]
      },
      {
        "title": "Deployments per Day",
        "type": "graph",
        "targets": [
          {
            "expr": "increase(deployments_total[1d])",
            "legendFormat": "Deployments"
          }
        ]
      }
    ]
  }
}
```

This deployment and CI/CD pipeline setup provides a robust foundation for continuous integration and deployment of the enterprise-grade multi-tenant backend platform, ensuring reliable, secure, and efficient delivery of application updates.