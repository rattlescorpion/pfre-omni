# ===========================================================================
# Property Finder Omni Platform — Makefile
# ===========================================================================
# Usage: make <target>
# Run `make help` to see all available commands.
# ===========================================================================

.DEFAULT_GOAL := help
.PHONY: help install up down restart logs shell tinker \
        migrate migrate-fresh seed migrate-rollback \
        test test-coverage lint format audit \
        cache-clear cache-warm key-gen ide-helper \
        queue-work horizon-start horizon-stop horizon-status \
        schedule-run schedule-list \
        build assets-watch \
        db-backup db-restore db-shell \
        redis-flush redis-cli redis-monitor \
        deploy-staging deploy-production \
        pfre-report pfre-snapshot pfre-queue-health \
        docker-build docker-push \
        setup clean storage-link permissions

# ---------------------------------------------------------------------------
# Colors
# ---------------------------------------------------------------------------
CYAN    := \033[0;36m
GREEN   := \033[0;32m
YELLOW  := \033[0;33m
RED     := \033[0;31m
RESET   := \033[0m
BOLD    := \033[1m

# ---------------------------------------------------------------------------
# Variables
# ---------------------------------------------------------------------------
DOCKER_COMPOSE  := docker compose
PHP             := $(DOCKER_COMPOSE) exec -T app php
ARTISAN         := $(PHP) artisan
COMPOSER        := $(DOCKER_COMPOSE) exec -T app composer
NPM             := $(DOCKER_COMPOSE) exec -T vite npm
APP_CONTAINER   := pfre_app
TIMESTAMP       := $(shell date +%Y%m%d_%H%M%S)
BACKUP_DIR      := storage/backups

# ===========================================================================
# HELP
# ===========================================================================
help: ## Show this help message
	@echo ""
	@echo "$(BOLD)$(CYAN)Property Finder Omni Platform — Make Commands$(RESET)"
	@echo "$(CYAN)━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━$(RESET)"
	@awk 'BEGIN {FS = ":.*##"; section=""} \
		/^# ====/ { gsub(/# ====+/, ""); printf "\n$(BOLD)$(YELLOW)%s$(RESET)\n", $$0; next } \
		/^[a-zA-Z_-]+:.*?##/ { printf "  $(GREEN)%-28s$(RESET) %s\n", $$1, $$2 }' \
		$(MAKEFILE_LIST)
	@echo ""

# ===========================================================================
# SETUP & INSTALLATION
# ===========================================================================

setup: ## Full first-time setup (install, key, migrate, seed, storage link)
	@echo "$(CYAN)Setting up PFRE Omni Platform...$(RESET)"
	@$(MAKE) install
	@$(ARTISAN) key:generate
	@$(MAKE) storage-link
	@$(MAKE) permissions
	@$(ARTISAN) migrate --seed
	@echo "$(GREEN)✅ Setup complete! Visit http://localhost$(RESET)"

install: ## Install all Composer + NPM dependencies
	@echo "$(CYAN)Installing dependencies...$(RESET)"
	$(COMPOSER) install
	$(NPM) install
	@echo "$(GREEN)✅ Dependencies installed$(RESET)"

key-gen: ## Generate application key
	$(ARTISAN) key:generate

storage-link: ## Create storage symlink
	$(ARTISAN) storage:link

permissions: ## Fix storage and cache directory permissions
	@echo "$(CYAN)Fixing permissions...$(RESET)"
	$(DOCKER_COMPOSE) exec -T app chmod -R 775 storage bootstrap/cache
	$(DOCKER_COMPOSE) exec -T app chown -R www-data:www-data storage bootstrap/cache
	@echo "$(GREEN)✅ Permissions fixed$(RESET)"

# ===========================================================================
# DOCKER
# ===========================================================================

up: ## Start all Docker services (detached)
	@echo "$(CYAN)Starting PFRE Omni services...$(RESET)"
	$(DOCKER_COMPOSE) up -d
	@echo "$(GREEN)✅ Services started$(RESET)"
	@echo "  App:           http://localhost"
	@echo "  phpMyAdmin:    http://localhost:8080"
	@echo "  Redis UI:      http://localhost:8081"
	@echo "  Mailpit:       http://localhost:8025"
	@echo "  Vite (HMR):    http://localhost:5173"

down: ## Stop all Docker services
	$(DOCKER_COMPOSE) down

restart: ## Restart all services
	$(DOCKER_COMPOSE) restart

logs: ## Tail application logs (all services)
	$(DOCKER_COMPOSE) logs -f

logs-app: ## Tail app container logs only
	$(DOCKER_COMPOSE) logs -f app

logs-nginx: ## Tail Nginx logs
	$(DOCKER_COMPOSE) logs -f nginx

logs-horizon: ## Tail Horizon queue worker logs
	$(DOCKER_COMPOSE) logs -f horizon

logs-scheduler: ## Tail scheduler container logs
	$(DOCKER_COMPOSE) logs -f scheduler

shell: ## Open a bash shell in the app container
	$(DOCKER_COMPOSE) exec app bash

shell-root: ## Open a root shell in the app container
	$(DOCKER_COMPOSE) exec -u root app bash

docker-build: ## Rebuild Docker images (no cache)
	$(DOCKER_COMPOSE) build --no-cache

# ===========================================================================
# ARTISAN
# ===========================================================================

tinker: ## Launch Laravel Tinker REPL
	$(DOCKER_COMPOSE) exec app php artisan tinker

ide-helper: ## Generate IDE helper files (PhpStorm)
	$(ARTISAN) ide-helper:generate
	$(ARTISAN) ide-helper:models --nowrite
	$(ARTISAN) ide-helper:meta

# ===========================================================================
# DATABASE
# ===========================================================================

migrate: ## Run pending migrations
	$(ARTISAN) migrate --force

migrate-fresh: ## Drop all tables and re-run migrations
	@echo "$(RED)⚠️  This will DESTROY all data. Press Ctrl+C to cancel.$(RESET)"
	@sleep 3
	$(ARTISAN) migrate:fresh

migrate-fresh-seed: ## Drop all tables, re-run migrations and seeders
	@echo "$(RED)⚠️  This will DESTROY all data. Press Ctrl+C to cancel.$(RESET)"
	@sleep 3
	$(ARTISAN) migrate:fresh --seed

migrate-rollback: ## Rollback the last batch of migrations
	$(ARTISAN) migrate:rollback

seed: ## Run all database seeders
	$(ARTISAN) db:seed

seed-module: ## Seed a specific module. Usage: make seed-module MODULE=LeadSeeder
	$(ARTISAN) db:seed --class=$(MODULE)

migrate-status: ## Show migration status
	$(ARTISAN) migrate:status

db-backup: ## Backup database to storage/backups/
	@mkdir -p $(BACKUP_DIR)
	$(DOCKER_COMPOSE) exec -T mysql mysqldump \
		-u root -p$${DB_ROOT_PASSWORD:-secret} \
		$${DB_DATABASE:-pfre_omni} > $(BACKUP_DIR)/db_backup_$(TIMESTAMP).sql
	@echo "$(GREEN)✅ Database backed up to $(BACKUP_DIR)/db_backup_$(TIMESTAMP).sql$(RESET)"

db-restore: ## Restore database. Usage: make db-restore FILE=path/to/file.sql
	@if [ -z "$(FILE)" ]; then echo "$(RED)❌ Usage: make db-restore FILE=path/to/backup.sql$(RESET)"; exit 1; fi
	$(DOCKER_COMPOSE) exec -T mysql mysql \
		-u root -p$${DB_ROOT_PASSWORD:-secret} \
		$${DB_DATABASE:-pfre_omni} < $(FILE)
	@echo "$(GREEN)✅ Database restored from $(FILE)$(RESET)"

db-shell: ## Open MySQL CLI shell
	$(DOCKER_COMPOSE) exec mysql mysql -u root -p$${DB_ROOT_PASSWORD:-secret} $${DB_DATABASE:-pfre_omni}

# ===========================================================================
# CACHE
# ===========================================================================

cache-clear: ## Clear all caches (config, route, view, cache)
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear
	$(ARTISAN) event:clear
	@echo "$(GREEN)✅ All caches cleared$(RESET)"

cache-warm: ## Build all caches for production
	$(ARTISAN) config:cache
	$(ARTISAN) route:cache
	$(ARTISAN) view:cache
	$(ARTISAN) event:cache
	@echo "$(GREEN)✅ Caches warmed$(RESET)"

# ===========================================================================
# QUEUE & HORIZON
# ===========================================================================

queue-work: ## Start a queue worker (foreground)
	$(ARTISAN) queue:work redis --queue=high,default,low --tries=3 --timeout=90

queue-restart: ## Gracefully restart all queue workers
	$(ARTISAN) queue:restart

horizon-start: ## Start Laravel Horizon in the container
	$(ARTISAN) horizon

horizon-stop: ## Terminate Horizon gracefully
	$(ARTISAN) horizon:terminate

horizon-status: ## Show Horizon status
	$(ARTISAN) horizon:status

horizon-pause: ## Pause Horizon processing
	$(ARTISAN) horizon:pause

horizon-continue: ## Resume Horizon processing
	$(ARTISAN) horizon:continue

# ===========================================================================
# SCHEDULER
# ===========================================================================

schedule-run: ## Run scheduled tasks now (one-time)
	$(ARTISAN) schedule:run --verbose

schedule-list: ## List all scheduled jobs
	$(ARTISAN) schedule:list

schedule-work: ## Start scheduler process (Laravel 10+)
	$(ARTISAN) schedule:work

# ===========================================================================
# TESTING
# ===========================================================================

test: ## Run PHPUnit test suite
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/phpunit --colors=always

test-coverage: ## Run tests with HTML coverage report
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/phpunit \
		--coverage-html storage/coverage \
		--colors=always
	@echo "$(GREEN)✅ Coverage report: storage/coverage/index.html$(RESET)"

test-filter: ## Run specific test. Usage: make test-filter FILTER=LeadTest
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/phpunit --filter=$(FILTER) --colors=always

test-group: ## Run test group. Usage: make test-group GROUP=crm
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/phpunit --group=$(GROUP) --colors=always

# ===========================================================================
# CODE QUALITY
# ===========================================================================

lint: ## Run PHP syntax check on all PHP files
	@find app config database routes tests -name "*.php" | xargs php -l
	@echo "$(GREEN)✅ PHP lint passed$(RESET)"

format: ## Run Laravel Pint code formatter
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/pint
	@echo "$(GREEN)✅ Code formatted$(RESET)"

format-check: ## Check code style without fixing
	$(DOCKER_COMPOSE) exec -T app ./vendor/bin/pint --test

audit: ## Run Composer security audit
	$(COMPOSER) audit

# ===========================================================================
# FRONTEND ASSETS
# ===========================================================================

build: ## Build frontend assets for production
	$(NPM) run build
	@echo "$(GREEN)✅ Assets built$(RESET)"

assets-watch: ## Start Vite dev server with HMR
	$(NPM) run dev

# ===========================================================================
# REDIS
# ===========================================================================

redis-flush: ## Flush all Redis keys (⚠️ clears cache + sessions)
	@echo "$(RED)⚠️  Flushing all Redis data. Press Ctrl+C to cancel.$(RESET)"
	@sleep 3
	$(DOCKER_COMPOSE) exec -T redis redis-cli FLUSHALL
	@echo "$(GREEN)✅ Redis flushed$(RESET)"

redis-cli: ## Open Redis CLI
	$(DOCKER_COMPOSE) exec redis redis-cli

redis-monitor: ## Monitor all Redis commands in real-time
	$(DOCKER_COMPOSE) exec redis redis-cli MONITOR

redis-info: ## Show Redis server info
	$(DOCKER_COMPOSE) exec redis redis-cli INFO

# ===========================================================================
# PFRE PLATFORM-SPECIFIC
# ===========================================================================

pfre-report: ## Generate today's daily MIS report
	$(ARTISAN) pfre:reports:daily-mis

pfre-snapshot: ## Capture KPI analytics snapshot
	$(ARTISAN) pfre:analytics:kpi-snapshot

pfre-queue-health: ## Check queue and Horizon health
	$(ARTISAN) pfre:system:queue-health-check

pfre-score-leads: ## Re-score all active leads immediately
	$(ARTISAN) pfre:leads:score-all

pfre-sync-payments: ## Sync pending Razorpay payment statuses
	$(ARTISAN) pfre:payments:razorpay-sync

pfre-gst-summary: ## Prepare current month GST summary
	$(ARTISAN) pfre:gst:prepare-gstr3b

pfre-payroll-run: ## Run payroll calculation for current month
	$(ARTISAN) pfre:payroll:calculate

pfre-backup: ## Run full platform backup (DB + storage)
	$(ARTISAN) backup:run
	@echo "$(GREEN)✅ Backup complete$(RESET)"

pfre-sync-attendance: ## Sync attendance from biometric devices now
	$(ARTISAN) pfre:attendance:sync

pfre-whatsapp-queue: ## Process pending WhatsApp message queue
	$(ARTISAN) pfre:whatsapp:process-queue

# ===========================================================================
# DEPLOYMENT
# ===========================================================================

deploy-staging: ## Deploy to staging server via SSH
	@echo "$(CYAN)Deploying to Staging...$(RESET)"
	git push origin staging
	@echo "$(GREEN)✅ Pushed to staging branch — CI/CD will take over$(RESET)"

deploy-production: ## Deploy to production (requires confirmation)
	@echo "$(RED)⚠️  You are about to deploy to PRODUCTION. Press Ctrl+C to cancel.$(RESET)"
	@sleep 5
	git push origin main
	@echo "$(GREEN)✅ Pushed to main branch — CI/CD will take over$(RESET)"

# ===========================================================================
# MAINTENANCE
# ===========================================================================

down-maintenance: ## Put app into maintenance mode
	$(ARTISAN) down --retry=60 --render="errors::503"

up-maintenance: ## Take app out of maintenance mode
	$(ARTISAN) up

clean: ## Remove compiled assets and caches
	$(MAKE) cache-clear
	rm -rf public/build
	rm -rf node_modules
	@echo "$(GREEN)✅ Cleaned$(RESET)"