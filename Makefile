# use example: make init
# docker login
# REGISTRY=apedchenko IMAGE_TAG=master-1 make build
# REGISTRY=apedchenko IMAGE_TAG=master-1 make push
# HOST=deploy@ip PORT=22 REGISTRY=apedchenko IMAGE_TAG=master-1 BUILD_NUMBER=1  make deploy
#init: docker-down-clear api-clear docker-pull docker-build docker-up api-init

# add rules: sudo chown $USER:$USER api/src/ -R
# '-' ignore any errors

init: docker-down-clear \
	api-clear frontend-clear cucumber-clear\
	docker-build docker-up \
	api-init frontend-init cucumber-init
up: docker-up
down: docker-down
restart: docker-down docker-up
check: lint analyze validate-schema test
lint: api-lint frontend-lint cucumber-lint
analyze: api-analyze
validate-schema: api-validate-schema
test: api-test api-fixtures frontend-test
test-coverage: api-test-coverage
test-unit: api-test-unit
test-unit-coverage: api-test-unit-coverage
test-functional: api-test-functional api-fixtures
test-functional-coverage: api-test-functional-coverage api-fixtures
test-smoke: api-fixtures cucumber-clear cucumber-smoke
test-e2e: api-fixtures cucumber-clear cucumber-e2e

docker-up:
	docker-compose up -d # --scale frontend=3

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build #--pull

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/*' # delete all items except with '.' in start

api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log

api-composer-install:
	docker-compose run --rm api-php-cli composer install

# check if db container is up (then can be work with data in db)
api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

# create migrations for db
api-migrations:
	docker-compose run --rm api-php-cli composer app migrations:migrate --no-interaction

# load fake data in local db
api-fixtures:
	docker-compose run --rm api-php-cli composer app fixtures:load

api-validate-schema:
	docker-compose run --rm api-php-cli composer app orm:validate-schema

api-lint:
	docker-compose run --rm api-php-cli composer lint # phplint - syntax check validator
	docker-compose run --rm api-php-cli composer cs-check # php_codesniffer - code style validator

api-analyze:
	docker-compose run --rm api-php-cli composer psalm -- --no-diff # static analysis tool for php without cache

api-analyze-diff:
	docker-compose run --rm api-php-cli composer psalm # static analysis tool for php with cache

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-coverage:
	docker-compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

# clear folders
frontend-clear:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install frontend-ready

frontend-yarn-install:
	docker-compose run --rm frontend-node-cli yarn install

# The .ready file is needed to start the server only after all packages have been installed
frontend-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready

# check code style for html / js
frontend-lint:
	docker-compose run --rm frontend-node-cli yarn eslint		# check js code style
	docker-compose run --rm frontend-node-cli yarn stylelint	# check css code style

# auto fix for js
frontend-eslint-fix:
	docker-compose run --rm frontend-node-cli yarn eslint-fix

# auto fix for css
frontend-stylelint-fix:
	docker-compose run --rm frontend-node-cli yarn stylelint-fix

# prettier linter (fix code styles by rules)
frontend-pretty:
	docker-compose run --rm frontend-node-cli yarn prettier

frontend-test:
	docker-compose run --rm frontend-node-cli yarn test --watchAll=false

frontend-test-watch:
	docker-compose run --rm frontend-node-cli yarn test

# clear var directory (with screenshots)
cucumber-clear:
	docker run --rm -v ${PWD}/cucumber:/app -w /app alpine sh -c 'rm -rf var/*'

cucumber-init: cucumber-yarn-install

# install cucumber js (for end-to-end tests)
cucumber-yarn-install:
	docker-compose run --rm cucumber-node-cli yarn install

# check js code style
cucumber-lint:
	docker-compose run --rm cucumber-node-cli yarn eslint

# check and fix js code style
cucumber-lint-fix:
	docker-compose run --rm cucumber-node-cli yarn eslint-fix

# smoke test (fast check if available website)
cucumber-smoke:
	docker-compose run --rm cucumber-node-cli yarn smoke

# run e2e tests
cucumber-e2e:
	docker-compose run --rm cucumber-node-cli yarn e2e

build: build-gateway build-frontend build-api

build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-gateway:${IMAGE_TAG} gateway/docker

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/auction-api-php-cli:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-gateway push-frontend push-api

push-gateway:
	docker push ${REGISTRY}/auction-gateway:${IMAGE_TAG}

push-frontend:
	docker push ${REGISTRY}/auction-frontend:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api:${IMAGE_TAG}

deploy:
	ssh ${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER}'
	ssh ${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}'
	scp -P ${PORT} docker-compose-production.yml ${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=auction" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_DB_PASSWORD=${API_DB_PASSWORD}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_HOST=${API_MAILER_HOST}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_PORT=${API_MAILER_PORT}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_USER=${API_MAILER_USER}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_PASSWORD=${API_MAILER_PASSWORD}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_FROM_EMAIL=${API_MAILER_FROM_EMAIL}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "SENTRY_DSN=${SENTRY_DSN}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build -d api-postgres api-php-cli'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose run api-php-cli wait-for-it api-postgres:5432 -t 60'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose run api-php-cli php bin/app.php migrations:migrate --no-interaction'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f site'
	ssh ${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'

rollback:
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f site'
	ssh ${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'