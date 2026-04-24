## ADDED Requirements

### Requirement: changelog:list shows all releases
The command `changelog:list` SHALL output a list of all published versions from `changelog.json`, sorted descending by version. Unreleased is not shown. Each row contains: version, release name, date, entry count.

#### Scenario: Releases present
- **WHEN** `changelog:list` is executed and releases exist
- **THEN** the command outputs one row per release, newest first

#### Scenario: No releases present
- **WHEN** `changelog:list` is executed and only unreleased exists
- **THEN** the command outputs an empty list and exits with code 0

#### Scenario: --json flag
- **WHEN** `changelog:list --json` is executed
- **THEN** the command outputs a JSON array: `[{"version": "...", "name": "...", "date": "...", "count": 5}]`

### Requirement: changelog:show shows entries for a version
The command `changelog:show` SHALL output the entries for a specific version or for unreleased. With `--version=<x.y.z>` that version is shown. With `--unreleased` the current unreleased items are shown. Without an argument `--unreleased` SHALL be the default.

#### Scenario: Show unreleased
- **WHEN** `changelog:show --unreleased` is executed
- **THEN** the command outputs all unreleased items grouped by type

#### Scenario: Show a specific version
- **WHEN** `changelog:show --version=1.1.0` is executed and that version exists
- **THEN** the command outputs the items for that version

#### Scenario: Version not found
- **WHEN** `changelog:show --version=9.9.9` is executed and that version does not exist
- **THEN** the command outputs an error message and exits with a non-zero exit code

#### Scenario: --json flag
- **WHEN** `changelog:show --unreleased --json` is executed
- **THEN** the command outputs the unreleased object as JSON
