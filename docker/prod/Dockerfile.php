# Utilisez l'image PHP-FPM pour Nginx
FROM php:8.2-fpm

# Installez les dépendances requises pour Symfony
RUN apt-get update && apt-get install -y \
	git \
	unzip \
	libzip-dev \
	libicu-dev \
	locales \
	&& docker-php-ext-install \
	pdo \
	pdo_mysql \
	zip \
	intl \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

# Ajout des locales FR
RUN locale-gen fr_FR.UTF-8

# Définissez le répertoire de travail pour notre application Symfony
WORKDIR /var/www/symfony

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
	mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Copier le reste du code source en premier
COPY . /var/www/symfony

# Copier ensuite composer.json et composer.lock et installer les dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Changer la propriété des fichiers vers www-data
RUN chown -R www-data:www-data /var/www/symfony

# Exposez le port sur lequel PHP-FPM écoute
EXPOSE 9000

# Commande pour lancer PHP-FPM
CMD ["php-fpm"]