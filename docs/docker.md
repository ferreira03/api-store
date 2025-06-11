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

# Copy project files
COPY . /var/www/html

# Install dependencies
RUN composer install

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
version: '3.8'

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
      - ./src:/var/www/html/src
      - ./config:/var/www/html/config
      - ./public:/var/www/html/public
      - ./var:/var/www/html/var
    networks:
      - api-store-network

  # Nginx Service
  nginx:
    image: nginx:alpine
    container_name: api-store-nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./public:/var/www/html
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - api-store-network

networks:
  api-store-network:
    driver: bridge
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
  - ./src:/var/www/html/src
  - ./config:/var/www/html/config
  - ./public:/var/www/html/public
  - ./var:/var/www/html/var
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
  - "8000:80"  # Host:Container
```

## Environment Variables

### Docker Environment
```yaml
environment:
  - APP_ENV=development
  - APP_DEBUG=true
  - DB_CONNECTION=sqlite
  - DB_DATABASE=/var/www/html/var/database/store.sqlite
```

### .env File
```env
APP_ENV=development
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=var/database/store.sqlite
```

## Development Tools

### Xdebug Configuration
```ini
[xdebug]
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.idekey=PHPSTORM
```

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

### Performance
- Enable OPcache
- Configure PHP-FPM
- Optimize Nginx
- Use production Composer settings

### Monitoring
- Configure logging
- Set up health checks
- Monitor container resources
- Track application metrics 