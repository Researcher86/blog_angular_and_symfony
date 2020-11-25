export WIN_HOST = $(shell cat /etc/resolv.conf | grep nameserver | awk '{print $$2; exit;}')

build:
	docker-compose build

up:
	docker-compose up -d

up-log:
	docker-compose up

down:
	docker-compose down -v

app-build:
	docker-compose run frontend bash -c "ng build --prod"

app-code-fix:
	docker-compose run backend bash -c "composer code-fix"

app-code-check:
	docker-compose run backend bash -c "composer code-fix"

app-test:
	docker-compose run backend bash -c "composer test"
#	docker-compose run frontend bash -c "ng test"

app-test-debug:
	docker-compose run backend bash -c "export XDEBUG_CONFIG='remote_autostart=1' && export PHP_IDE_CONFIG='serverName=dev.blog.kz' && composer test"
#	docker-compose run frontend bash -c "ng test"


.PHONY: up-log
