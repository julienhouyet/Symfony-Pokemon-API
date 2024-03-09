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

# Exposez le port sur lequel PHP-FPM écoute
EXPOSE 9000

# Commande pour lancer PHP-FPM
CMD ["php-fpm"]
