.PHONY: run re start stop test fixture

run:
	php bin/console cache:clear
	docker-compose -f docker/dev/docker-compose.yml down
	composer install
	npm install
	docker-compose -f docker/dev/docker-compose.yml up -d
	yarn run watch

re:
	php bin/console cache:clear
	docker-compose -f docker/dev/docker-compose.yml down
	composer install
	npm install
	docker-compose -f docker/dev/docker-compose.yml up --build -d
	yarn run watch

start:
	docker-compose -f docker/dev/docker-compose.yml up -d

stop:
	docker-compose -f docker/dev/docker-compose.yml down

fixture:
	php bin/console doctrine:fixtures:load

test:
	php ./vendor/bin/phpunit