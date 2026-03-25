.PHONY: help

help: ## Print help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

up: ## Project launch
	@docker compose up -d
	@npm run dev

setup: ## Complete initial configuration of the Laravel project
	@docker compose build
	@docker compose up -d
	@cp -n .env.example .env
	@echo "Waiting for MySQL..."
	@until docker compose exec php php -r "try { new PDO('mysql:host=mysql;dbname=laravel-pet-db', 'root', 'password'); echo 'OK'; } catch (Exception $$e) { exit(1); }"; do sleep 2; done
	@docker compose exec php php artisan migrate
	@npm run build

stop: ## Stopping containers
	@docker compose stop

down: ## Removing containers with volumes
	@docker compose down --volumes