.PHONY: bench coverage cs infection it test

it: cs test bench

bench: vendor
	composer require --dev zendframework/zend-file:^2.7.1
	vendor/bin/phpbench run --report=aggregate
	composer remove --dev zendframework/zend-file

coverage: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

cs: vendor
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	vendor/bin/infection --min-covered-msi=87 --min-msi=87

test: vendor
	vendor/bin/phpunit --configuration=test/AutoReview/phpunit.xml
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
