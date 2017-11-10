COMPOSE=docker-compose
APP=$(COMPOSE) exec php
COMPOSER=$(APP) composer
SYMFONY_CONSOLE=$(APP) bin/console

help:           ## Show this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

install:        ## Setup the project
install: build install_php_deps

start:          ## Start Docker containers
	docker-compose up -d

build:          ## Build Docker containers
	docker-compose build

stop:           ## Stop Docker containers
	docker-compose stop

install_php_deps:
	$(COMPOSER) install

generate_entities:
	$(SYMFONY_CONSOLE) doctrine:generate:entities AppBundle --no-backup
