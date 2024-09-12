phpunit:
	vendor/bin/phpunit

phpspec:
	vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan:
	vendor/bin/phpstan analyse

backend:
	composer install
	tests/Application/bin/console sylius:install --no-interaction
	tests/Application/bin/console sylius:fixtures:load default --no-interaction

drop-db:
	tests/Application/bin/console d:d:d --force

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

init: install backend frontend

tests: install phpspec phpstan phpunit
