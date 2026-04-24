## Why

AI developer tools (Claude Code, Cursor) and CI/CD pipelines cannot currently consume the changelog directly — CLI output is human-readable only, read commands are missing, and there is no MCP interface. Since changelogs are maintained on feature branches during development and released in pipelines, both workflows need to be machine-friendly.

## What Changes

- `--json` flag for all Artisan commands: returns structured JSON output instead of formatted text
- `changelog:list` command: lists all releases (version, name, date, entry count)
- `changelog:show` command: shows entries for a version (`--version=1.1.0`) or unreleased (`--unreleased`)
- `changelog:suggest-release` command: derives the recommended version bump from unreleased items (feat→minor, fix/chore→patch, breaking→major)
- MCP Server (`vendor/bin/changelog-mcp`): standalone PHP script that speaks MCP JSON-RPC over stdio without a Laravel bootstrap; package ships with `.mcp.json.example`

## Capabilities

### New Capabilities

- `json-output`: `--json` flag on all commands for machine-readable output
- `changelog-read`: new read commands `changelog:list` and `changelog:show`
- `suggest-release`: automatic recommendation of version bump type from unreleased items
- `mcp-server`: standalone MCP server for native AI tool integration

### Modified Capabilities

## Impact

- `src/Commands/` — all existing commands gain `--json` support; three new command classes
- `src/Commands/BaseCommand.php` — JSON output logic as base method
- `bin/changelog-mcp` — new standalone PHP script (no Laravel dependency)
- `.mcp.json.example` — new file in the package root
- `composer.json` — register `bin/changelog-mcp` as composer bin
- No change to `changelog.json` structure (consumer-compatible)
