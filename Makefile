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
	@sleep 5
	@docker compose exec php php artisan migrate
	@npm run build

stop: ## Stopping containers
	@docker compose stop

down: ## Removing containers with volumes
	@docker compose down --volumes