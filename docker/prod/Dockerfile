# Utilisez l'image PHP-FPM pour Nginx
FROM php:8.2-fpm AS php_builder

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

# Copiez seulement composer.json et composer.lock et installez les dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Utilisez l'image Node.js pour construire les assets Vue.js
FROM node:latest as node_builder

# Définissez le répertoire de travail pour les opérations Node.js
WORKDIR /app

# Copiez le reste des sources des assets depuis votre contexte de build
COPY . /app

# Copiez le dossier vendor de l'étape php_builder
COPY --from=php_builder /var/www/symfony/vendor ./vendor

# Assurez-vous de copier le reste des fichiers nécessaires pour yarn install
COPY package.json yarn.lock ./

# Installez les dépendances Node.js
RUN yarn install

# Compilez les assets
RUN yarn run build

# Retour à l'image PHP pour la configuration finale
FROM php:8.2-fpm

# Définissez le répertoire de travail pour votre application Symfony finale
WORKDIR /var/www/symfony

# Copiez le code source Symfony et les assets compilés depuis les étapes précédentes
COPY --from=php_builder /var/www/symfony .
COPY --from=node_builder /app/public ./public

# Changez la propriété des fichiers vers www-data
RUN chown -R www-data:www-data .

# Exposez le port sur lequel PHP-FPM écoute
EXPOSE 9000

# Commande pour lancer PHP-FPM
CMD ["php-fpm"]
