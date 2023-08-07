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

## Doc

### laravel-model-doc

````
composer require romanzipp/laravel-model-doc --dev
php artisan vendor:publish --provider="romanzipp\ModelDoc\Providers\ModelDocServiceProvider"
````

````shell
php artisan model-doc:generate
````

### Eloquent phpDoc

````composer require sethphat/eloquent-docs --dev````

https://github.com/sethsandaru/eloquent-docs

https://dev.to/sethsandaru/laravel-generate-phpdoc-properties-for-your-eloquent-models-4k78

Usage

````shell
php artisan eloquent:phpdoc App\Models\User # view only
php artisan eloquent:phpdoc App\Models\User --write # view & write to file
php artisan eloquent:phpdoc App\Models\User --short-class # new option - use short class instead of full namespace path
````

## Test data

### Alice

https://github.com/nelmio/alice

````composer require --dev nelmio/alice````

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
