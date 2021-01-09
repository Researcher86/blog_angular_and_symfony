export WIN_HOST = $(shell cat /etc/resolv.conf | grep nameserver | awk '{print $$2; exit;}')

test-build:
	docker-compose build --parallel --build-arg BUILDKIT_INLINE_CACHE=1

build-dev-docker-images:
	docker-compose -f docker-compose.build.dev.yml -f docker-compose.dev.yml build --force-rm

build-prod-docker-images:
	docker-compose -f docker-compose.build.prod.yml -f docker-compose.prod.yml build --force-rm

push-prod-docker-images:
	docker-compose -f docker-compose.build.prod.yml -f docker-compose.prod.yml push

up-dev:
	docker-compose -f docker-compose.dev.yml up -d

up-prod:
	docker-compose -f docker-compose.prod.yml up -d

up-log-dev:
	docker-compose -f docker-compose.dev.yml up

up-log-prod:
	docker-compose -f docker-compose.prod.yml up

restart-dev:
	docker-compose -f docker-compose.dev.yml restart

restart-prod:
	docker-compose -f docker-compose.prod.yml restart

up-new: app-install app-init

down-dev:
	docker-compose -f docker-compose.dev.yml down -v --remove-orphans

down-prod:
	docker-compose -f docker-compose.prod.yml down -v --remove-orphans

nginx-reload-config:
	docker-compose -f docker-compose.dev.yml exec web-server sh -c "nginx -s reload"

app-build:
	docker-compose -f docker-compose.dev.yml run --rm frontend sh -c "yarn install && npm run build"

app-message-failed:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer message-failed"

app-message-failed-retry:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer message-failed-retry"

app-code-fix:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer code-fix"

app-code-check:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer code-check"

app-install:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer install"

app-init:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "\
		wait-for-it db:5432 -s -t 60 && \
		wait-for-it es:9200 -s -t 60 && \
		composer app-init"

app-cache-update: app-install

app-backend:
	docker-compose -f docker-compose.dev.yml exec backend bash

app-php-cli: wait
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash

app-php-cli-debug: wait
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "\
	  	export XDEBUG_CONFIG=client_host=${WIN_HOST} && \
        export XDEBUG_MODE=debug && \
        export XDEBUG_SESSION=PHPSTORM && \
        export PHP_IDE_CONFIG=serverName=php-cli && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
        bash"

app-frontend:
	docker-compose -f docker-compose.dev.yml exec frontend bash

app-worker-restart:
	docker-compose -f docker-compose.dev.yml restart worker-indexer
	docker-compose -f docker-compose.dev.yml restart worker-plagiarism
	docker-compose -f docker-compose.dev.yml restart worker-email
	docker-compose -f docker-compose.dev.yml restart worker-telegram

app-worker-stop:
	docker-compose -f docker-compose.dev.yml stop worker-indexer
	docker-compose -f docker-compose.dev.yml stop worker-plagiarism
	docker-compose -f docker-compose.dev.yml stop worker-email
	docker-compose -f docker-compose.dev.yml stop worker-telegram

app-worker-start:
	docker-compose -f docker-compose.dev.yml start worker-indexer
	docker-compose -f docker-compose.dev.yml start worker-plagiarism
	docker-compose -f docker-compose.dev.yml start worker-email
	docker-compose -f docker-compose.dev.yml start worker-telegram

wait:
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "\
		wait-for-it db:5432 -s -t 60 && \
		wait-for-it redis:6379 -s -t 60 && \
		wait-for-it rabbit-mq:5672 -s -t 60 && \
		wait-for-it es:9200 -s -t 60 && \
		wait-for-it mailer:8025 -s -t 60 && \
		wait-for-it centrifugo:8000 -s -t 60"

app-test: wait
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-debug: wait
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "\
	  	export XDEBUG_CONFIG=client_host=${WIN_HOST} && \
        export XDEBUG_MODE=debug && \
        export XDEBUG_SESSION=PHPSTORM && \
        export PHP_IDE_CONFIG=serverName=php-cli && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
		composer test"
#	docker-compose run --rm frontend bash -c "ng test"

app-test-coverage: wait
	docker-compose -f docker-compose.dev.yml run --rm php-cli bash -c "\
        export XDEBUG_MODE=coverage && \
        export | grep -E 'XDEBUG|PHP_IDE' && \
		composer coverage"

.PHONY: up-log
