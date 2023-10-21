# How to Contribute

## Pull Requests

1. Fork the repository
2. Create a new branch for each feature or improvement
3. Send a pull request from each feature branch to the **develop** branch

It is very important to separate new features or improvements into separate feature branches, and to send a
pull request for each branch. This allows me to review and pull in new features or improvements individually.

## Style Guide

All pull requests must adhere to the [PSR-12 standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md).

You may lint the codebase manually using the following commands:

``` bash
# Lint
composer run dev:lint:style

# Attempt to auto-fix coding standards issues
composer run dev:lint:fix
```

## Unit Testing

All pull requests must be accompanied by passing unit tests and complete code coverage. The Slim Framework uses phpunit for testing.

To run all the tests and coding standards checks, execute the following from the
command line, while in the project root directory:

```
composer run dev:test
```

[Learn about PHPUnit](https://github.com/sebastianbergmann/phpunit/)

### Static Analysis

This project uses a combination of [PHPStan](https://github.com/phpstan/phpstan)
and [Psalm](https://github.com/vimeo/psalm) to provide static analysis of PHP
code.

CaptainHook will run static analysis checks before committing.

You may run static analysis manually across the whole codebase with the
following command:

``` bash
# Static analysis
composer run dev:analyze
```