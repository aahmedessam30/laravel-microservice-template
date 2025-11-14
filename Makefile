.PHONY: help install start stop restart test format analyse logs clean build shell composer artisan

# Default target
.DEFAULT_GOAL := help

# Colors for output
YELLOW := \033[1;33m
GREEN := \033[1;32m
NC := \033[0m # No Color
 
help: ## Show this help message
	@echo "$(GREEN)Laravel Microservice Template - Makefile Commands$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'
	@echo ""

install: ## Install dependencies and setup environment
	@echo "$(GREEN)Installing dependencies...$(NC)"
	composer install
	npm install
	cp -n .env.example .env || true
	php artisan key:generate
	@echo "$(GREEN)Installation complete!$(NC)"

start: ## Start Docker containers
	@echo "$(GREEN)Starting containers...$(NC)"
	docker-compose up -d
	@echo "$(GREEN)Containers started!$(NC)"

stop: ## Stop Docker containers
	@echo "$(GREEN)Stopping containers...$(NC)"
	docker-compose down
	@echo "$(GREEN)Containers stopped!$(NC)"

restart: stop start ## Restart Docker containers

build: ## Build Docker images
	@echo "$(GREEN)Building Docker images...$(NC)"
	docker-compose build
	@echo "$(GREEN)Build complete!$(NC)"

test: ## Run tests
	@echo "$(GREEN)Running tests...$(NC)"
	php artisan test

format: ## Format code with Laravel Pint
	@echo "$(GREEN)Formatting code...$(NC)"
	php vendor/bin/pint

analyse: ## Run PHPStan static analysis
	@echo "$(GREEN)Running static analysis...$(NC)"
	php vendor/bin/phpstan analyse

logs: ## Show Docker container logs
	docker-compose logs -f

clean: ## Clean cache and temporary files
	@echo "$(GREEN)Cleaning cache...$(NC)"
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	@echo "$(GREEN)Cache cleared!$(NC)"

shell: ## Access PHP container shell
	docker-compose exec php-fpm bash

composer: ## Run composer command (usage: make composer CMD="install")
	docker-compose exec php-fpm composer $(CMD)

artisan: ## Run artisan command (usage: make artisan CMD="migrate")
	docker-compose exec php-fpm php artisan $(CMD)

migrate: ## Run database migrations
	@echo "$(GREEN)Running migrations...$(NC)"
	php artisan migrate

migrate-fresh: ## Fresh migration with seed
	@echo "$(GREEN)Fresh migration...$(NC)"
	php artisan migrate:fresh --seed

setup: install build start migrate ## Complete setup (install + build + start + migrate)
	@echo "$(GREEN)Setup complete! Application is ready.$(NC)"
