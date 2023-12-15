
COMPOSER = composer
CONSOLE = bin/console
PHP = php

##
##Code Quality
##------------
##

.PHONY: qa
qa: ## Run all code quality checks
qa: lint-containers phpcs php-cs-fixer phpstan

.PHONY: qa-fix
qa-fix: ## Run all code quality fixers
qa-fix: phpinsights-fix php-cs-fixer-apply

.PHONY: lint-containers
lint-container: ## Lints containers
	# Need PHP dependencies (run "make composer-install" if needed)
	$(CONSOLE) lint:container

.PHONY: phpcs
phpcs: ## PHP_CodeSniffer (https://github.com/squizlabs/PHP_CodeSniffer)
	$(PHP) vendor/bin/phpcs -p -n --colors --standard=.phpcs.xml

.PHONY: phpcs-tests
phpcs-tests: ## PHP_CodeSniffer (https://github.com/squizlabs/PHP_CodeSniffer)
	$(PHP) vendor/bin/phpcs -p -n --colors --standard=.phpcs-tests.xml

.PHONY: php-cs-fixer
php-cs-fixer: ## PhpCsFixer (https://cs.symfony.com/)
	$(PHP) vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff --dry-run

.PHONY: php-cs-fixer-apply
php-cs-fixer-apply: ## Applies PhpCsFixer fixes
	$(PHP) vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff

.PHONY: phpstan
phpstan: ## PHP Static Analysis Tool (https://github.com/phpstan/phpstan)
	$(PHP) vendor/bin/phpstan --configuration=phpstan.neon --memory-limit=1G

.PHONY: phpinsights
phpinsights: ## PHP Insights (https://phpinsights.com)
	$(PHP) vendor/bin/phpinsights analyse src --no-interaction -vvv

.PHONY: phpinsights-fix
phpinsights-fix: ## PHP Insights (https://phpinsights.com)
	$(PHP) vendor/bin/phpinsights analyse src --no-interaction --fix

##
##Tests
##------------
##

.PHONY: tests
tests: ## Run all tests
tests: phpunit behat

.PHONY: phpunit
phpunit: ## Run unit tests
	$(PHP) ./vendor/bin/phpunit

.PHONY: behat
behat: ## Run functional tests
	$(PHP) ./vendor/bin/behat

##
##Dependencies validation
##-----------------------

composer-outdated: ## Check outdated PHP packages
	# Need PHP dependencies (run "make composer-install" if needed)
	$(COMPOSER) outdated --direct --strict
