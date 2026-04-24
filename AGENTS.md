# AGENTS.md

This file provides guidance to AI coding agents when working with code in this repository.

## Commands

```bash
composer test              # Run Pest tests
composer test-coverage     # Run tests with coverage report
composer analyse           # PHPStan analysis (Level 4)
composer format            # Fix code style with Pint
```

Run a single test:
```bash
vendor/bin/pest tests/Commands/AddChangelogTest.php
vendor/bin/pest --filter="test name"
```

## Architecture

This is a **Laravel Package** (not an application) — tested via Orchestra Testbench.

### Core Workflow

1. `changelog:add` → writes to `resources/.changes/changelog.json` (unreleased section)
2. `changelog:release` → increments version in `resources/.version/version.yml`, moves unreleased → versioned entry in changelog.json
3. `changelog:generate-md` → renders `CHANGELOG.md` via Blade template

### Key Components

**`src/Logic/`** — Core logic:
- `VersionHandling` — reads/writes `version.yml`, increments versions; registered as singleton
- `VersionCalculator` — static methods for semver calculations (major/minor/patch/prerelease)
- `Version` — formats version strings using configurable templates; registered as singleton under `releasechangelog.version`

**`src/Commands/`** — Artisan commands (all under the `changelog:*` namespace)

**`src/Util/`**:
- `FileHandler` — central path management for all file operations
- `VersionUtil` — version update logic by type (patch/minor/major/rc/timestamp)
- `Constants` — app-wide constants

**`src/Data/ChangelogItem.php`** — DTO for individual changelog entries

**`src/ServiceProvider.php`** — registers commands, singletons, Blade directive (`@releasechangelog`), publishes assets

### Data Files

`resources/.version/version.yml` — version state:
```yaml
major: 1 / minor: 0 / patch: 1 / prerelease: rc / prereleasenumber: 0 / buildmetadata: null
```

`resources/.changes/changelog.json` — changelog data:
```json
{
  "unreleased": { "name": "tbd", "release": false, "feat": [...], "modules": [...] },
  "1.0.1.rc0":  { "name": "My First Release", "date": "...", "release": true, "feat": [...] }
}
```

`resources/views/changelog-md.blade.php` — Blade template for CHANGELOG.md generation

### Configuration (`config/config.php`, key: `releasechangelog`)

- `version_formats` — named templates with placeholders like `{major}`, `{minor}`, `{patch}`, `{prerelease}`, `{timestamp}`, etc.
- `prerelease` — whether prerelease components are active
- `blade-directive` — name of the Blade directive (default: `releasechangelog`)
- `markdown-path` — output path for CHANGELOG.md

### Module Support

Changelog entries can be assigned to modules (`--module=core`). In `changelog.json` these are stored under `modules[].{type}[]` instead of directly under the release entry.

### Testing

Tests run with Pest v4 + Orchestra Testbench. `tests/TestCase.php` copies `version.yml`, `changelog.json` and views into a temporary directory before each test and cleans up afterwards — tests never write to `resources/`.
