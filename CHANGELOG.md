# Changelog

All notable changes to `laravel-release-changelog-generator` will be documented in this file

## 1.1.0 Svartisen - 2026-04-24

🏔️  1.1.0 Svartisen


---

🤖 AI & Pipeline Integration

This release makes the changelog fully machine-readable — designed for AI developer tools, CI/CD pipelines,
and MCP-compatible editors.


---

✨ What's New

🔧 --json flag for all Artisan commands
Every command now supports --json for structured, parseable output — no ANSI codes, no free text.
php artisan changelog:show-version --json

### {"version": "1.1.0"}

📋 changelog:list — List all releases
Lists all published versions with name, date, and entry count. Newest first.
php artisan changelog:list --json

### [{"version":"1.1.0","name":"Svartisen","date":"...","count":6}]

🔍 changelog:show — Show release entries
Show unreleased items or a specific version.
php artisan changelog:show --unreleased --json
php artisan changelog:show --ver=1.0.0

🧠 changelog:suggest-release — Auto-detect bump type
Analyses unreleased entries and recommends patch, minor, or major. Pipeline-ready:
TYPE=$(php artisan changelog:suggest-release --json | jq -r '.type')
Rule: breaking → major · feat/feature → minor · everything else → patch

⚡ MCP Server (vendor/bin/changelog-mcp)
Standalone PHP script — no Laravel bootstrap, runs as a subprocess for AI tools like Claude Code or Cursor.
Tools: add_entry · get_unreleased · get_version · list_releases · create_release

Copy .mcp.json.example to .mcp.json in your project to get started.


---

Full Changelog: https://github.com/lightszentip/laravel-release-changelog-generator/compare/1.0.0...1.1.0

## 1.0.0 Monacobreen - 2025-09-02

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

Feature Modules support in json by @lightszentip in https://github.com/lightszentip/laravel-release-changelog-generator/pull/262

#### Other Changes

* chore(deps): update devdependencies (major) (major) by @renovate[bot] in https://github.com/lightszentip/laravel-release-changelog-generator/pull/256
* chore(deps): update actions/checkout action to v5 by @renovate[bot] in https://github.com/lightszentip/laravel-release-changelog-generator/pull/259

**Full Changelog**: https://github.com/lightszentip/laravel-release-changelog-generator/compare/0.4.1...1.0.0

## 0.4.1 🌈 - 2025-06-19

### Changes

- Update README.md to comprehensively explain what the package do @iabduul7 (#253)
- fix: tests @lightszentip (#249)

### 📦 Dependencies

- chore(config): migrate renovate config @[renovate[bot]](https://github.com/apps/renovate) (#257)
- chore(deps): update stefanzweifel/git-auto-commit-action action to v6 @[renovate[bot]](https://github.com/apps/renovate) (#255)
- chore(deps): update dependabot/fetch-metadata action to v2.4.0 @[renovate[bot]](https://github.com/apps/renovate) (#254)
- chore(deps): update orhun/git-cliff-action action to v4 @[renovate[bot]](https://github.com/apps/renovate) (#251)
- chore(deps): update dependabot/fetch-metadata action to v2.2.0 @[renovate[bot]](https://github.com/apps/renovate) (#250)

## 0.4.0 🌈  Gran Paradiso - 2024-06-01

### Changes

- update: new yaml version @lightszentip (#248)

### IMPORTANT!!!

* Run only with PHP 8.2
* Works only with Laravel 11

## 0.3.5 🌈 Finsteraarhorn - 2024-06-01

### Changes

- If prerelease is zero - remove the block from version @lightszentip (#247)
- Generated Changelog now sorts by version @lightszentip (#116)

### 📦 Dependencies

- chore(deps): update dependabot/fetch-metadata action to v2 @renovate (#226)
- build(deps-dev): bump orchestra/testbench from 8.22.3 to 8.23.0 @dependabot (#246)
- build(deps-dev): bump phpstan/phpstan-deprecation-rules from 1.1.4 to 1.2.0 @dependabot (#243)
- build(deps-dev): bump phpstan/phpstan-phpunit from 1.3.16 to 1.4.0 @dependabot (#241)
- build(deps-dev): bump vimeo/psalm from 5.23.1 to 5.24.0 @dependabot (#240)
- build(deps): bump symfony/yaml from 6.4.3 to 6.4.7 @dependabot (#239)
- build(deps-dev): bump pestphp/pest-plugin-laravel from 2.3.0 to 2.4.0 @dependabot (#237)

## 0.3.1 🌈 Elbrus - 2023-03-27

### Changes

### 🚀 Features

- feat: add custom path for markdown view @lightszentip (#67)

## 0.3.0 🌞 Jostedalsbreen - 2023-03-25

### Changes

### 🚀 Features

- Generate a Markdown Changelog File @robbanl  (#60) - thanks 👍

### 📦 Dependencies

- Pest2 integration @lightszentip (#59)
- build(deps-dev): bump phpstan/phpstan-deprecation-rules from 1.1.2 to 1.1.3 @dependabot (#58)
- build(deps-dev): bump friendsofphp/php-cs-fixer from 3.15.0 to 3.15.1 @dependabot (#57)
- build(deps-dev): bump orchestra/testbench from 8.0.8 to 8.0.10 @dependabot (#55)
- build(deps-dev): bump phpunit/phpunit from 9.6.4 to 9.6.5 @dependabot (#51)
- build(deps-dev): bump friendsofphp/php-cs-fixer from 3.14.4 to 3.15.0 @dependabot (#50)
- build(deps-dev): bump vimeo/psalm from 5.7.7 to 5.8.0 @dependabot (#49)
- build(deps-dev): bump orchestra/testbench from 8.0.5 to 8.0.8 @dependabot (#48)
- build(deps): bump symfony/yaml from 6.2.5 to 6.2.7 @dependabot (#43)
- build(deps-dev): bump pestphp/pest from 1.22.4 to 1.22.5 @dependabot (#45)
- build(deps-dev): bump orchestra/testbench from 8.0.4 to 8.0.5 @dependabot (#44)
- build(deps-dev): bump phpunit/phpunit from 9.6.3 to 9.6.4 @dependabot (#42)
- build(deps-dev): bump psalm/plugin-laravel from 2.7.1 to 2.8.0 @dependabot (#41)
- build(deps-dev): bump phpstan/phpstan-phpunit from 1.3.8 to 1.3.10 @dependabot (#40)
- build(deps-dev): bump phpstan/phpstan-deprecation-rules from 1.1.1 to 1.1.2 @dependabot (#39)
- build(deps-dev): bump phpstan/phpstan-phpunit from 1.3.4 to 1.3.8 @dependabot (#37)
- build(deps-dev): bump orchestra/testbench from 8.0.1 to 8.0.4 @dependabot (#35)
- chore(deps): update dependency orchestra/testbench to v8 @renovate (#31)
- build(deps-dev): bump vimeo/psalm from 5.6.0 to 5.7.1 @dependabot (#34)
- build(deps-dev): bump psalm/plugin-laravel from 2.7.0 to 2.7.1 @dependabot (#32)
- build(deps-dev): bump orchestra/testbench from 7.21.0 to 7.22.0 @dependabot (#30)
- build(deps-dev): bump friendsofphp/php-cs-fixer from 3.14.3 to 3.14.4 @dependabot (#29)
- build(deps-dev): bump phpstan/phpstan-phpunit from 1.3.3 to 1.3.4 @dependabot (#28)
- build(deps-dev): bump pestphp/pest from 1.22.3 to 1.22.4 @dependabot (#26)
- build(deps-dev): bump phpunit/phpunit from 9.5.28 to 9.6.3 @dependabot (#25)
- build(deps-dev): bump psalm/plugin-laravel from 2.5.0 to 2.7.0 @dependabot (#24)
- build(deps): bump symfony/http-kernel from 6.2.5 to 6.2.6 @dependabot (#22)
- build(deps): bump symfony/yaml from 6.2.2 to 6.2.5 @dependabot (#20)
- build(deps-dev): bump psalm/plugin-laravel from 2.3.1 to 2.5.0 @dependabot (#19)
- build(deps-dev): bump friendsofphp/php-cs-fixer from 3.13.2 to 3.14.3 @dependabot (#18)
- chore(deps): update dependabot/fetch-metadata action to v1.3.6 @renovate (#17)
- build(deps-dev): bump vimeo/psalm from 5.4.0 to 5.5.0 @dependabot (#16)
- build(deps-dev): bump psalm/plugin-laravel from 2.0.2 to 2.3.1 @dependabot (#15)
- build(deps-dev): bump phpunit/phpunit from 9.5.27 to 9.5.28 @dependabot (#14)
- build(deps-dev): bump pestphp/pest-plugin-laravel from 1.3.0 to 1.4.0 @dependabot (#13)
- build(deps-dev): bump friendsofphp/php-cs-fixer from 3.13.1 to 3.13.2 @dependabot (#12)

## 0.2.1 🎄 Brenibba - 2022-12-26

### Changes

### 🐛 Bug Fixes

- fix : Version not work as blade directive - please use @releasechangelog

### 📦 Dependencies

- chore(deps): update orhun/git-cliff-action action to v2 @renovate (#11)

## 0.2.0 🎄 Beerenberg - 2022-12-23

### 🚀 Features

- Set Release by version from input over artisan @lightszentip (#10)
- feat: Version update for artisan and update timestamp always @lightszentip (#9)
- Feature - new artisan command to show version @lightszentip (#8)

### 📦 Dependencies

- chore(deps): update actions/checkout action to v3 @renovate (#6)

## 0.1.1 🌈 Austre Leirungstinden - 2022-12-23

### Changes

### 🐛 Bug Fixes

- fix: change the constant for default format to 'full' @lightszentip (#5)

## 0.1.0 🌈 Austre Hestbreapiggen - 2022-12-22

### Changes

- Bugfix/test @lightszentip (#4)

### 📦 Dependencies

- build(deps-dev): bump phpstan/phpstan-phpunit from 1.3.2 to 1.3.3 @dependabot (#2)
- build(deps): bump symfony/yaml from 6.0.16 to 6.2.2 @dependabot (#1)
