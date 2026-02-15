.SILENT:
.DEFAULT_GOAL:= help

COLOR_DEFAULT=\033[0m
COLOR_RED=\033[31m
COLOR_GREEN=\033[32m
COLOR_YELLOW=\033[33m

VENDOR=vendor/bin/
STAN=$(VENDOR)phpstan --configuration=dev/phpstan.neon
TEST=$(VENDOR)phpunit --configuration=dev/phpunit.xml
AUDIT=composer audit

.PHONY: help
help: ## Shows the help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | awk 'BEGIN {FS = ":"}; {printf "$(COLOR_YELLOW)%s:$(COLOR_DEFAULT)%s\n\n", $$1, $$2}'

.PHONY: stan
stan: ## Run stan analyze
	$(STAN)

.PHONY: test
test: ## Run unit test
	$(TEST)

.PHONY: audit
audit: ## Run composer audit
	$(AUDIT)

.PHONY: pipeline
pipeline: ## Run full pipeline (audit, stan, test)
	$(AUDIT)
	$(STAN)
	$(TEST)