# Docker Configuration for API STORE

## Overview
This document describes the Docker setup for the API STORE project, including container configuration, networking, and development workflow.

## Dockerfile

```dockerfile
# Use PHP 8.1 FPM as base image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create necessary directories
RUN mkdir -p /var/www/html/public \
    /var/www/html/src \
    /var/www/html/config \
    /var/www/html/var \
    /var/www/html/vendor

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/public
RUN chmod -R 777 /var/www/html/var

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
```

## Docker Compose

```yaml
services:
  # PHP Service
  php:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: api-store-php
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - ./fixtures:/var/www/html/fixtures
    networks:
      - api-store-network
    depends_on:
      - mysql

  # Nginx Service
  nginx:
    image: nginx:alpine
    container_name: api-store-nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./public:/var/www/html/public
      - ./docker/nginx:/etc/nginx/conf.d
    depends_on:
      - php
    networks:
      - api-store-network

  # MySQL Service
  mysql:
    image: mysql:8.0
    container_name: api-store-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: store_db
      MYSQL_ROOT_PASSWORD: root
      MYSQL_PASSWORD: root
      MYSQL_USER: store_user
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - api-store-network

networks:
  api-store-network:
    driver: bridge

volumes:
  mysql_data:
```

## Nginx Configuration

```nginx
server {
    listen 80;
    index index.php index.html;
    server_name localhost;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

## Development Workflow

### Starting the Environment
```bash
# Build and start containers
docker-compose up -d

# View logs
docker-compose logs -f
```

### Running Commands
```bash
# Execute composer commands
docker-compose exec php composer install
docker-compose exec php composer update

# Run tests
docker-compose exec php vendor/bin/phpunit

# Access container shell
docker-compose exec php sh
```

### Stopping the Environment
```bash
# Stop containers
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

## Volume Management

### Persistent Volumes
```yaml
volumes:
  - .:/var/www/html
  - ./fixtures:/var/www/html/fixtures
  - mysql_data:/var/lib/mysql
```

### Volume Permissions
```bash
# Set proper permissions
chmod -R 755 public/
chmod -R 777 var/
```

## Networking

### Container Network
```yaml
networks:
  api-store-network:
    driver: bridge
```

### Port Mapping
```yaml
ports:
  - "8000:80"  # Nginx
  - "3306:3306"  # MySQL
```

## Environment Variables

### MySQL Environment
```yaml
environment:
  MYSQL_DATABASE: store_db
  MYSQL_ROOT_PASSWORD: root
  MYSQL_PASSWORD: root
  MYSQL_USER: store_user
```

## Development Tools

### Composer Configuration
```json
{
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true
    }
}
```

## Production Considerations

### Security
- Use non-root user
- Remove development tools
- Set proper permissions
- Use production PHP settings
- Secure MySQL credentials
- Configure proper firewall rules

### Performance
- Enable OPcache
- Configure PHP-FPM
- Optimize Nginx
- Use production Composer settings
- Configure MySQL for production

### Monitoring
- Configure logging
- Set up health checks
- Monitor container resources
- Track application metrics
- Monitor MySQL performance 