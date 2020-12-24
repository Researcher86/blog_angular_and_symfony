export WIN_HOST = $(shell cat /etc/resolv.conf | grep nameserver | awk '{print $$2; exit;}')

test-build:
	docker-compose build --parallel --build-arg BUILDKIT_INLINE_CACHE=1

build:
	docker-compose build

up:
	docker-compose up -d

up-log:
	docker-compose up

up-new: app-install app-init

down:
	docker-compose down -v --remove-orphans

nginx-reload-config:
	docker-compose exec web-server sh -c "nginx -s reload"

app-build:
	docker-compose run --rm frontend bash -c "ng build --prod"

app-message-failed:
	docker-compose run --rm php-cli bash -c "composer message-failed"

app-message-failed-retry:
	docker-compose run --rm php-cli bash -c "composer message-failed-retry"

app-code-fix:
	docker-compose run --rm php-cli bash -c "composer code-fix"

app-code-check:
	docker-compose run --rm php-cli bash -c "composer code-check"

app-install:
	docker-compose run --rm php-cli bash -c "composer install"

app-init:
	docker-compose run --rm php-cli bash -c "\
		wait-for-it db:5432 -s -t 60 && \
		wait-for-it es:9200 -s -t 60 && \
		composer app-init"

app-cache-update: app-install

app-backend:
	docker-compose exec backend bash

app-php-cli: wait
	docker-compose run --rm php-cli bash

app-php-cli-debug: wait
	docker-compose run --rm php-cli bash -c "\
	  	export XDEBUG_CONFIG=client_host=${WIN_HOST} && \
        export XDEBUG_MODE=debug && \
        export XDEBUG_SESSION=PHPSTORM && \
        export PHP_IDE_CONFIG=serverName=php-cli && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
        bash"

app-frontend:
	docker-compose exec frontend bash

app-worker-restart:
	docker-compose restart worker-indexer
	docker-compose restart worker-plagiarism
	docker-compose restart worker-email
	docker-compose restart worker-telegram

wait:
	docker-compose run --rm php-cli bash -c "\
		wait-for-it db:5432 -s -t 60 && \
		wait-for-it redis:6379 -s -t 60 && \
		wait-for-it rabbit-mq:5672 -s -t 60 && \
		wait-for-it es:9200 -s -t 60 && \
		wait-for-it mailer:8025 -s -t 60 && \
		wait-for-it centrifugo:8000 -s -t 60"

app-test: wait
	docker-compose run --rm php-cli bash -c "composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-debug: wait
	docker-compose run --rm php-cli bash -c "\
	  	export XDEBUG_CONFIG=client_host=${WIN_HOST} && \
        export XDEBUG_MODE=debug && \
        export XDEBUG_SESSION=PHPSTORM && \
        export PHP_IDE_CONFIG=serverName=php-cli && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
		composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-coverage: wait
	docker-compose run --rm --name blog_php_cli php_cli bash -c "\
        export XDEBUG_MODE=coverage && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
		composer coverage"

.PHONY: up-log
