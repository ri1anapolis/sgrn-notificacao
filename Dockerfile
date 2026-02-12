
FROM php:8.3-fpm

ARG user=sgrn
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    zip \
    unzip \
    gnupg \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install --no-scripts --no-autoloader \
    && npm install

COPY . .

RUN composer dump-autoload --optimize

RUN npm run build

RUN rm -rf node_modules

RUN chown -R $user:www-data /var/www/storage /var/www/bootstrap/cache

USER $user

EXPOSE 9000

CMD ["php-fpm"]
