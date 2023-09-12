PROJECT_NAME?=shop
COMPOSE_FILE?=docker/docker-compose.yml

logs:
	docker-compose -p ${PROJECT_NAME} -f ${COMPOSE_FILE} logs -ft ${SERVICE}

ps:
	docker-compose -p ${PROJECT_NAME} -f ${COMPOSE_FILE} ps

ssh:
	docker exec -it ${PROJECT_NAME}_fpm bash

build:
	docker-compose -f ${COMPOSE_FILE} build --no-cache

rm: stop
	rm -rf var/log/*.log
	docker-compose -p ${PROJECT_NAME} -f ${COMPOSE_FILE} rm -fv

stop:
	docker-compose -p ${PROJECT_NAME} -f ${COMPOSE_FILE} stop -t0

run: rm
	docker-compose -p ${PROJECT_NAME} -f ${COMPOSE_FILE} up -d --remove-orphans

test:
	docker exec -i ${PROJECT_NAME}_fpm /code/vendor/bin/phpunit
