.PHONY: bench coverage cs infection it stan test

it: cs stan test bench

bench: vendor
	composer require --dev zendframework/zend-file:^2.7.1
	vendor/bin/phpbench run --report=aggregate
	composer remove --dev zendframework/zend-file

coverage: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

cs: vendor
	mkdir -p .php-cs-fixer
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	mkdir -p .infection
	vendor/bin/infection --ignore-msi-with-no-mutations --min-covered-msi=87 --min-msi=87

stan: vendor
	mkdir -p .phpstan
	vendor/bin/phpstan analyse --configuration=phpstan.neon src test

test: vendor
	vendor/bin/phpunit --configuration=test/AutoReview/phpunit.xml
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
	composer normalize
