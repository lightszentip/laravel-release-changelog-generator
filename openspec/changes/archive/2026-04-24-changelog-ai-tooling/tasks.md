## 1. --json Flag in BaseCommand

- [x] 1.1 Add `--json` option to BaseCommand signature
- [x] 1.2 Implement `outputJson(array $data): int` method in BaseCommand
- [x] 1.3 Implement `errorJson(string $message): int` method in BaseCommand for error cases
- [x] 1.4 Extend all existing commands (add, release, set-release, update-version, show-version, generate-md) with JSON output
- [x] 1.5 Add tests for the --json flag in existing commands

## 2. changelog:list Command

- [x] 2.1 Create `src/Commands/ListChangelog.php` (extends BaseCommand)
- [x] 2.2 Read releases from changelog.json, sort by version, exclude unreleased
- [x] 2.3 Tabular output (version, name, date, entry count)
- [x] 2.4 JSON output: array with `version`, `name`, `date`, `count`
- [x] 2.5 Register command in ServiceProvider
- [x] 2.6 Write tests for ListChangelog

## 3. changelog:show Command

- [x] 3.1 Create `src/Commands/ShowChangelog.php` (extends BaseCommand)
- [x] 3.2 `--ver=` option: load a specific version from changelog.json (--version is reserved by Symfony, hence --ver)
- [x] 3.3 `--unreleased` option: load the unreleased section (default when no flag is given)
- [x] 3.4 Error handling when version does not exist
- [x] 3.5 JSON output: full version object
- [x] 3.6 Register command in ServiceProvider
- [x] 3.7 Write tests for ShowChangelog

## 4. changelog:suggest-release Command

- [x] 4.1 Create `src/Commands/SuggestRelease.php` (extends BaseCommand)
- [x] 4.2 Implement rule logic: iterate unreleased items, type mapping (breaking→major, feat/feature→minor, rest→patch)
- [x] 4.3 Text output with recommendation and reasoning
- [x] 4.4 JSON output: `{"type": "...", "reason": "..."}`
- [x] 4.5 Register command in ServiceProvider
- [x] 4.6 Tests for all scenarios (only fix, feat present, breaking present, empty, unknown types)

## 5. MCP Server

- [x] 5.1 Create `bin/changelog-mcp` PHP script
- [x] 5.2 Implement MCP JSON-RPC stdin/stdout loop (initialize, tools/list, tools/call)
- [x] 5.3 Implement tool `add_entry` (writes directly to changelog.json)
- [x] 5.4 Implement tool `get_unreleased`
- [x] 5.5 Implement tool `get_version` (reads version.yml, formats by template)
- [x] 5.6 Implement tool `list_releases`
- [x] 5.7 Implement tool `create_release` (version bump + changelog update without Laravel)
- [x] 5.8 Wire up ENV variables `CHANGELOG_PATH` and `VERSION_PATH` with defaults
- [x] 5.9 Register script in `composer.json` under `bin`
- [x] 5.10 Create `.mcp.json.example` in package root
- [x] 5.11 Add README section for MCP server setup
