# Dockerfile
# Use base image for container
FROM richarvey/nginx-php-fpm:3.1.6

# Copy all application code into your Docker container
COPY . .

RUN apk update

# Install the `npm` package
RUN apk add --no-cache npm

# Install NPM dependencies
RUN npm install

# Build Vite assets
RUN npm run build

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]