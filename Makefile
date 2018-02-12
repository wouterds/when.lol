all: tag

PWD = $(shell pwd)

clean:
	-rm -rf $(PWD)/node_modules
	-rm -rf $(PWD)/package-lock.json
	-rm -rf $(PWD)/vendor
	-rm -f $(PWD)/composer.phar
	-rm -f $(PWD)/composer-setup.php

composer.phar:
	docker run --rm --volume=$(PWD):/code -w=/code php:7.2-alpine php -r 'copy("https://getcomposer.org/installer", "./composer-setup.php");'
	docker run --rm --volume=$(PWD):/code -w=/code php:7.2-alpine php ./composer-setup.php
	docker run --rm --volume=$(PWD):/code -w=/code php:7.2-alpine php -r 'unlink("./composer-setup.php");'

vendor: composer.phar composer.json composer.lock
	docker run --rm --volume=$(PWD):/code -w=/code php:7.2-alpine php ./composer.phar install --ignore-platform-reqs --prefer-dist --no-progress --optimize-autoloader

node_modules: package.json
	docker run --rm --volume=$(PWD):/code -w=/code node:8-slim npm install
