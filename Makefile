export WIN_HOST = $(shell cat /etc/resolv.conf | grep nameserver | awk '{print $$2; exit;}')

build:
	docker-compose build

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
	docker-compose run --rm backend bash -c "composer message-failed"

app-message-failed-retry:
	docker-compose run --rm backend bash -c "composer message-failed-retry"

app-code-fix:
	docker-compose run --rm backend bash -c "composer code-fix"

app-code-check:
	docker-compose run --rm backend bash -c "composer code-fix"

app-init:
	docker-compose run --rm backend bash -c "composer app-init"

app-update-cache:
	docker-compose run --rm backend bash -c "composer install"

app-backend:
	docker-compose exec backend bash

app-frontend:
	docker-compose exec frontend bash

app-restart-worker:
	docker-compose restart worker_indexer
	docker-compose restart worker_email

app-test:
	docker-compose run --rm backend bash -c "composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-debug:
	docker-compose run --rm backend bash -c "export XDEBUG_CONFIG='remote_autostart=1' && export PHP_IDE_CONFIG='serverName=dev.blog.kz' && composer test"
#	docker-compose run --rm frontend bash -c "ng test"


.PHONY: up-log
