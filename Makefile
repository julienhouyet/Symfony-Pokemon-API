GREEN=\033[0;32m
RED=\033[0;31m
YELLOW=\033[0;33m
BLUE=\033[0;34m
NC=\033[0m

VERSION=0.6.0
LATEST_VERSION = $(shell curl -s "https://api.github.com/repos/julienhouyet/Symfony-Pokemon-API/releases?per_page=1" | grep tag_name | sed 's/.*: "\(.*\)",/\1/' | sed 's/v//')

help:
	@echo "\nWelcome to ${GREEN}Symfony Pokemon API${NC} version ${YELLOW}${VERSION}${NC} (c) 2024 Julien Houyet\n"
	@if [ "$(VERSION)" != "$(LATEST_VERSION)" ]; then \
		echo "${RED}Version $(VERSION) is obsolete. The latest version is $(LATEST_VERSION).\n${NC}"; \
	fi
	@echo "These are common commands used in various situations.\n"
	@echo "Please use 'make <target>'\n"
	@echo "${YELLOW}Work on a project locally :${NC}\n"
	@echo "  ${GREEN}setup\t\t\t${NC} Execute the complete setup process"
	@echo "  ${GREEN}start\t\t\t${NC} Start the project"
	@echo "  ${GREEN}stop\t\t\t${NC} Stop the project"
	@echo "  ${GREEN}rebuild\t\t${NC} Rebuild the project and start it"
	@echo "  ${GREEN}fixture\t\t${NC} Load fixtures into the database"
	@echo "  ${GREEN}test\t\t\t${NC} Run PHPUnit tests"
	@echo "  ${GREEN}migration\t\t${NC} Prepare a new database migration based on schema changes"
	@echo "  ${GREEN}migrate\t\t${NC} Apply database migrations to update the database schema"

rebuild:
	@echo "${YELLOW}Stopping containers...${NC}"
	docker-compose -f docker/dev/docker-compose.yml down
	@echo "${YELLOW}Clearing cache...${NC}"
	symfony php bin/console cache:clear
	@echo "${YELLOW}Installing dependencies...${NC}"
	composer install
	npm install
	@echo "${YELLOW}Building and starting containers...${NC}"
	docker-compose -f docker/dev/docker-compose.yml up --build -d
	@echo "${YELLOW}Watching for changes...${NC}"
	yarn run watch

setup:
	@echo "${YELLOW}Installing dependencies...${NC}"
	composer install
	npm install
	@echo "${YELLOW}Building and starting containers...${NC}"
	docker-compose -f docker/dev/docker-compose.yml build
	@echo "\n\nInstallation complete, run ${GREEN}make start${NC} to launch the project.\n"

start:
	@if [ ! -d "vendor" ] || [ ! -d "node_modules" ]; then \
		echo "${YELLOW}Dependencies not found. Running setup...${NC}"; \
		make setup; \
	fi
	@echo "${YELLOW}Starting containers...${NC}"
	docker-compose -f docker/dev/docker-compose.yml up -d
	@echo "${YELLOW}Watching for changes...${NC}"
	yarn run watch

stop:
	@echo "${YELLOW}Stopping containers...${NC}"
	docker-compose -f docker/dev/docker-compose.yml down

fixture:
	@echo "${YELLOW}Loading fixtures...${NC}"
	docker exec -it symfony-pokemon-api-php-1 php bin/console doctrine:fixtures:load

migration:
	@echo "${YELLOW}Prepare migration...${NC}"
	docker exec -it symfony-pokemon-api-php-1 php bin/console make:migration

migrate:
	@echo "${YELLOW}Prepare migrate...${NC}"
	docker exec -it symfony-pokemon-api-php-1 php bin/console doctrine:migrations:migrate

test:
	@echo "${YELLOW}Running PHPUnit tests...${NC}"
	docker exec -it symfony-pokemon-api-php-1 php bin/phpunit

.PHONY: help rebuild setup start stop test fixture migrate migration
