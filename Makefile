BEHAT ?= $(PWD)/hack/php-noxdebug $(PWD)/vendor/bin/behat
BEHAT_TAGS ?= $(shell php utils/behat-tags.php)

.PHONY: test
test:
	$(BEHAT) --format progress $(BEHAT_TAGS) --strict

.PHONY: lint
lint:
	composer lint

.PHONY: dep
dep:
	composer install
