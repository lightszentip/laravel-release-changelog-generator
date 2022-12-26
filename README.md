# Package to create releases and changelogs in laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lightszentip/laravel-release-changelog-generator.svg?style=flat-square)](https://packagist.org/packages/lightszentip/laravel-release-changelog-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/lightszentip/laravel-release-changelog-generator/run-tests?label=tests)](https://github.com/lightszentip/laravel-release-changelog-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/lightszentip/laravel-release-changelog-generator/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/lightszentip/laravel-release-changelog-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lightszentip/laravel-release-changelog-generator.svg?style=flat-square)](https://packagist.org/packages/lightszentip/laravel-release-changelog-generator) [![PHP-CS-Fixer](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/quality-check.yml/badge.svg?branch=main)](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/quality-check.yml) [![Update Changelog GIT](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/update-changelog-main.yml/badge.svg)](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/update-changelog-main.yml)

This package add a changelog management over command line and a version release management. So you can add new changelog items and if you release, the changelog ist update with new version and the version file also. So it is easy to manage a customer changelog.

The changelog is a json file that can easy integrate into a controller to present to end customer.

See [ROADMAP](ROADMAP.md) for new functions.

## Installation

You can install the package via composer:

```bash
composer require lightszentip/laravel-release-changelog-generator
```

Please publish the necessary files with:

```bash
php artisan vendor:publish --provider="Lightszentip\LaravelReleaseChangelogGenerator\ServiceProvider"
```

## Usage

If the command use without arguments, the command line create a ask for all necessary arguments.

### Create a new changelog item:
```shell
php artisan changelog:add --type="feat" --message="Implement the whole function for magic"
```

You can find the result in resources/.changes/changelog.json

### Release the changelog

```shell
php artisan changelog:release --releasename="My First Release" --type=patch
```

This update the version.yml to the next patch vesion and add in the changelog.json a new release with all current changelog items.

### Get version in the application 

#### Blade

```php
@version
```
To get a special format for the version, you find in the 'releasechangelog.php' in the config dir the item 'version_formats'. You can add new formats and/or change the existing. To use it:

```php
@version('full')
```
#### Code

```php
app('releasechangelog.version')->showVersion($format)
```

### Config

````prerelease```` set to false to remove the "rc" part from version

To use antother pre release name as 'rc', change it on version.yml direct.

### Example Result

#### version.yml

```
label: v
major: 1
minor: 0
patch: 1
prerelease: rc
prereleasenumber: 0
buildmetadata: null
timestamp:
    date: null
    timestamp: null
```

#### changelog.json

```
{"unreleased":{"name":"tbd","date":"","release":false},
"1.0.1.rc0":{"name":"My First Release","date":"2022-12-22 23:56:34","release":true,"feat":[{"message":"My first feature"},{"message":"Implement the whole function for magic"}]}}
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Lightszentip](https://github.com/lightszentip)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
