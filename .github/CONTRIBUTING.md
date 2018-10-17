# CONTRIBUTING

We're using [Travis CI](https://travis-ci.com) as a continuous integration system.

For details, see [`.travis.yml`](../.travis.yml).

## Tests

We're using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit) to drive the development.

Run

```
$ make test
```

to run all the tests.

## Benchmarks

We're using [`phpbench/phpbench`](http://github.com/phpbench/phpbench) to benchmark performance and memory consumption.

Run

```
$ make bench
```

to run all the benchmarks.

## Coding Standards

We are using [`friendsofphp/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to enforce coding standards.

Run

```
$ make cs
```

to automatically fix coding standard violations.

## Extra lazy?

Run

```
$ make
```

to run coding standards check, tests, and benchmarks!
