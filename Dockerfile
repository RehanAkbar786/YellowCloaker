# Use the official PHP image
FROM php:8.0-apache

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy application code to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 8000
EXPOSE 8000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
