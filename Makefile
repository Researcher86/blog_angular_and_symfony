export WIN_HOST = $(shell cat /etc/resolv.conf | grep nameserver | awk '{print $$2; exit;}')

build:
	docker-compose build

up-new: app-install app-init

up:
	docker-compose up -d

up-log:
	docker-compose up

down:
	docker-compose down -v

nginx-reload-config:
	docker-compose exec web-server sh -c "nginx -s reload"

app-build:
	docker-compose run --rm frontend bash -c "ng build --prod"

app-message-failed:
	docker-compose run --rm php_cli bash -c "composer message-failed"

app-message-failed-retry:
	docker-compose run --rm php_cli bash -c "composer message-failed-retry"

app-code-fix:
	docker-compose run --rm php_cli bash -c "composer code-fix"

app-code-check:
	docker-compose run --rm php_cli bash -c "composer code-check"

app-init:
	docker-compose run --rm php_cli bash -c "composer app-init"

app-cache-update: app-install

app-install:
	docker-compose run --rm backend bash -c "composer install"

app-backend:
	docker-compose exec backend bash

app-php-cli:
	docker-compose run --rm php_cli bash

app-frontend:
	docker-compose exec frontend bash

app-restart-worker:
	docker-compose restart worker_indexer
	docker-compose restart worker_email

app-test:
	docker-compose run --rm php_cli bash -c "composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-debug:
	docker-compose run --rm php_cli bash -c "\
	  	export XDEBUG_CONFIG=client_host=${WIN_HOST} && \
        export XDEBUG_MODE=debug && \
        export XDEBUG_SESSION=PHPSTORM && \
        export PHP_IDE_CONFIG=serverName=php-cli && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
		composer test"
#	docker-compose run --rm frontend bash -c "ng test"


.PHONY: up-log
