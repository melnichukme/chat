# Global
install: env-create build up composer-install migrate fixtures-load

up:
	@docker compose up -d
down:
	@docker compose down
restart:
	@docker compose down
	@docker compose up -d

env-create:
	@cp .env.example .env
build:
	@docker compose build
composer-install:
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader"
composer-install-no-dev:
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-dev"

migrate:
	@make exec cmd="php bin/console doctrine:migrations:migrate --no-interaction"
migrate-diff:
	@make exec cmd="php bin/console doctrine:migrations:diff"

fixtures-load:
	@make exec cmd="php bin/console doctrine:fixtures:load --no-interaction"

# Terminal
exec:
	@docker compose exec php-fpm $$cmd
exec-root:
	@docker compose exec -u root php-fpm $$cmd
exec-bash:
	@docker compose exec php-fpm bash -c "$(cmd)"
terminal:
	@docker compose exec php-fpm bash
