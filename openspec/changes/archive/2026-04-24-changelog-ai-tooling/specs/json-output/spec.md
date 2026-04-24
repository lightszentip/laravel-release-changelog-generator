## ADDED Requirements

### Requirement: All commands support the --json flag
All Artisan commands of the package (add, release, set-release, update-version, show-version, generate-md, list, show, suggest-release) SHALL accept a `--json` flag. When the flag is set, the command outputs a JSON string to stdout instead of formatted text. The exit code remains unchanged.

#### Scenario: Successful command with --json
- **WHEN** a command is executed with `--json` and succeeds
- **THEN** the command outputs a valid JSON string and exits with code 0

#### Scenario: Failed command with --json
- **WHEN** a command is executed with `--json` and fails
- **THEN** the command outputs `{"error": "<message>"}` to stdout and exits with a non-zero exit code

### Requirement: JSON output is machine-readable and structured
The JSON output of a command SHALL contain all relevant output data as fields. No free text, no ANSI color codes.

#### Scenario: show-version with --json
- **WHEN** `changelog:show-version --json` is executed
- **THEN** the command outputs `{"version": "<string>"}`

#### Scenario: suggest-release with --json for pipelines
- **WHEN** `changelog:suggest-release --json` is executed
- **THEN** the command outputs `{"type": "patch|minor|major", "reason": "<string>"}`, usable with `jq -r '.type'`
