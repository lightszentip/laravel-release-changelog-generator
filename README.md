# Package to create releases and changelogs in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lightszentip/laravel-release-changelog-generator.svg?style=flat-square)](https://packagist.org/packages/lightszentip/laravel-release-changelog-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/lightszentip/laravel-release-changelog-generator.svg?style=flat-square)](https://packagist.org/packages/lightszentip/laravel-release-changelog-generator)
[![PHP-CS-Fixer](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/quality-check.yml/badge.svg?branch=main)](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/quality-check.yml)
[![Update Changelog GIT](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/update-changelog-main.yml/badge.svg)](https://github.com/lightszentip/laravel-release-changelog-generator/actions/workflows/update-changelog-main.yml)

This package adds changelog management over the command line and version release management. You can add new changelog items, and if you release, the changelog is updated with the new version and the version file. Thus, it is easy to manage a customer changelog.

The changelog is a JSON file that can easily be integrated into a controller to present to the end customer.

See [ROADMAP](ROADMAP.md) for new functions.

*HINT*: Please run 'php artisan vendor:publish --tag=resources'

## Version Compatibility

 Release Changelog Generator | PHP   | Laravel             | 
:----------------------------|:------|:--------------------
 0.1.x                       | >=8.0 | 9                   
 0.2.0                       | >=8.0 | 9                   
 0.2.1                       | >=8.0 | 9 / 10 (not tested) 
 0.3.x                       | >=8.1 | 9 / 10              
 0.4.x                       | >=8.2 | 11              
 1.0.x                       | >=8.3 | 11, 12              
 1.1.x                       | >=8.4 | 12, 13              
## Installation

You can install the package via Composer:

```bash
composer require lightszentip/laravel-release-changelog-generator
```

Please publish the necessary files with:

```bash
php artisan vendor:publish --provider="Lightszentip\LaravelReleaseChangelogGenerator\ServiceProvider"
php artisan vendor:publish --tag=resources
```

## Usage

If the command is used without arguments, the command line creates an ask for all necessary arguments.

### Create a new changelog item:

```shell
php artisan changelog:add --type="feat" --message="Implement the whole function for magic"
php artisan changelog:add --type="fix" --message="Fix the magic" --issue="42"
php artisan changelog:add --type="feat" --message="Module feature" --module="core"
```

Options: `--type`, `--message`, `--issue` (optional issue reference), `--module` (optional module name).

You can find the result in resources/.changes/changelog.json

### Release the changelog

```shell
php artisan changelog:release --releasename="My First Release" --type=patch
```

This updates the version.yml to the next patch version and add in the changelog.json a new release with all current
changelog items.

### Set a specific release version:

```shell
php artisan changelog:set-release --releasename="My Release" --versionnumber="2.1.0"
```

### Update the version without creating a release:

```shell
php artisan changelog:update-version --type=patch
```

Types: `patch`, `minor`, `major`, `rc`, `timestamp`

### Show the current version:

```shell
php artisan changelog:show-version
php artisan changelog:show-version --format=full
```

### Update CHANGELOG.md file:

This will update the CHANGELOG.md file in the root with your changes.

```shell
php artisan changelog:generate-md
```

### Get the version in the application

#### Blade

```php
@releasechangelog
```

To get a special format for the version, you find in the 'releasechangelog.php' in the config dir the item '
version_formats'. You can add new formats and/or change the existing ones. To use it:

```php
@releasechangelog('full')
```

#### Code

```php
app('releasechangelog.version')->showVersion($format)
```

### Config

````prerelease```` set to false to remove the "rc" part from version

To use another pre-release name as 'rc', change it on version.yml direct.

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

## AI & Pipeline Integration

### JSON Output Flag

Every Artisan command supports `--json` for machine-readable output:

```shell
php artisan changelog:show-version --json
# {"version":"1.0.0"}

php artisan changelog:suggest-release --json
# {"type":"minor","reason":"Feature type 'feat' found in unreleased entries"}

TYPE=$(php artisan changelog:suggest-release --json | jq -r '.type')
php artisan changelog:release --releasename="My Release" --type="$TYPE"
```

### Read Commands

```shell
# List all released versions
php artisan changelog:list
php artisan changelog:list --json

# Show unreleased entries (default) or a specific version
php artisan changelog:show
php artisan changelog:show --unreleased --json
php artisan changelog:show --ver=1.0.0 --json
```

### MCP Server (Claude Code / AI Tools)

The package ships a standalone MCP server that AI tools (Claude, Cursor, etc.) can use to read and write the changelog without a full Laravel bootstrap.

**Setup:**

1. Copy the example config to your project root:
   ```shell
   cp vendor/lightszentip/laravel-release-changelog-generator/.mcp.json.example .mcp.json
   ```

2. Adjust paths in `.mcp.json` if your project uses non-default locations:
   ```json
   {
     "mcpServers": {
       "changelog": {
         "command": "vendor/bin/changelog-mcp",
         "env": {
           "CHANGELOG_PATH": "resources/.changes/changelog.json",
           "VERSION_PATH":   "resources/.version/version.yml"
         }
       }
     }
   }
   ```

**Available MCP Tools:**

| Tool | Description |
|------|-------------|
| `add_entry` | Add a changelog entry to unreleased (`type`, `message`, optional `module`/`issue`) |
| `get_unreleased` | Return the current unreleased section |
| `get_version` | Return the current version (optional `format` parameter) |
| `list_releases` | List all released versions |
| `create_release` | Bump version and create a new release (`name`, `type`: patch/minor/major/rc) |

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
