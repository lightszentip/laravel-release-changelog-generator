# Code

## Quality

### PHP Coding Standards Fixer

``composer require --dev  friendsofphp/php-cs-fixer``

````shell
vendor/bin/php-cs-fixer fix src
```` 

### PHPStan

````composer require --dev phpstan/phpstan````

````shell
vendor/bin/phpstan analyse src tests
````

## Analyse

### PHPloc

*HINT* Deprecated

composer require --dev phploc/phploc

Usage:

````shell
php .\vendor\phploc\phploc\phploc src
````

### PHPcd

*HINT* Deprecated

````shell
composer require --dev sebastian/phpcpd
````

Usage:

````shell
php .\vendor\sebastian\phpcpd\phpcpd src 
````

### Checkstyle

https://github.com/PHPCheckstyle/phpcheckstyle

````shell
composer require --dev phpcheckstyle/phpcheckstyle
````

Usage:

````shell
 php .\vendor\phpcheckstyle\phpcheckstyle\run.php --src .\src\
 php .\vendor\phpcheckstyle\phpcheckstyle\run.php --src .\src\ --format html --outdir ./build/style-report
````

### PSALM

https://github.com/vimeo/psalm

````shell
composer require --dev vimeo/psalm
./vendor/bin/psalm --init
````

Usage:

````shell
./vendor/bin/psalm
````

#### Plugins

https://psalm.dev/plugins

````shell
composer require --dev psalm/plugin-laravel && vendor/bin/psalm-plugin enable psalm/plugin-laravel
composer require --dev psalm/plugin-phpunit && vendor/bin/psalm-plugin enable psalm/plugin-phpunit
````

## Other

https://github.com/phpro/grumphp

https://github.com/squizlabs/PHP_CodeSniffer

## Test run

```shell
./vendor/bin/pest --no-coverage
```
