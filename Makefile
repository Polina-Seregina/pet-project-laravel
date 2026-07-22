MINIO_ALIAS = minio
MINIO_URL = http://localhost:9000
MINIO_ROOT_USER = laravel
MINIO_ROOT_PASSWORD = password
MINIO_BUCKET = local
ENV_FILE = .env
ENV_EXAMPLE_FILE = .env.example

.PHONY: help up setup env stop down

help: ## Print help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

up: ## Project launch
	@docker-compose up -d
	@npm run dev

setup: env ## Complete initial configuration of the Laravel project
	@docker-compose build
	@docker-compose up -d
	@echo "Waiting for MinIO..."
	@until curl -sf $(MINIO_URL)/minio/health/live > /dev/null; do sleep 2; done
	@mc alias set $(MINIO_ALIAS) $(MINIO_URL) $(MINIO_ROOT_USER) $(MINIO_ROOT_PASSWORD)
	@mc mb $(MINIO_ALIAS)/$(MINIO_BUCKET) || true
	@mc anonymous set public $(MINIO_ALIAS)/$(MINIO_BUCKET)
	@echo "Waiting for MySQL..."
	@until docker-compose exec php php -r "try { new PDO('mysql:host=mysql;dbname=laravel-pet-db', 'root', 'password'); echo 'OK'; } catch (Exception $$e) { exit(1); }"; do sleep 2; done
	@docker-compose exec php php artisan migrate
	@docker-compose exec php php artisan db:seed --class=RoleSeeder
	@npm run build

env: ## Create local environment file
	@if [ ! -f "$(ENV_EXAMPLE_FILE)" ]; then \
		echo "Missing $(ENV_EXAMPLE_FILE)"; \
		exit 1; \
	fi
	@if [ ! -f "$(ENV_FILE)" ]; then \
		cp "$(ENV_EXAMPLE_FILE)" "$(ENV_FILE)"; \
		echo "Created $(ENV_FILE) from $(ENV_EXAMPLE_FILE)"; \
	else \
		echo "$(ENV_FILE) already exists, keeping current file"; \
	fi

stop: ## Stopping containers
	@docker-compose stop

down: ## Removing containers with volumes
	@mc rb --force $(MINIO_ALIAS)/$(MINIO_BUCKET) || true
	@mc alias remove $(MINIO_ALIAS) || true
	@docker compose down --volumes