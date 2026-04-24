## ADDED Requirements

### Requirement: MCP server runs as a standalone PHP script
The package SHALL ship an executable PHP script at `bin/changelog-mcp` that communicates as an MCP server over stdio (JSON-RPC). The script MUST work without a Laravel bootstrap and reads file paths from the environment variables `CHANGELOG_PATH` and `VERSION_PATH`. Composer registers the script as a `bin` entry.

#### Scenario: Script callable via vendor/bin
- **WHEN** `vendor/bin/changelog-mcp` is executed
- **THEN** the MCP server starts and waits for JSON-RPC input on stdin

#### Scenario: Paths configurable via ENV
- **WHEN** `CHANGELOG_PATH=/custom/path.json vendor/bin/changelog-mcp` is executed
- **THEN** the server reads/writes the specified file

### Requirement: MCP server exposes changelog tools
The MCP server SHALL provide the following tools via the MCP protocol: `add_entry`, `get_unreleased`, `get_version`, `list_releases`, `create_release`.

#### Scenario: add_entry tool
- **WHEN** an MCP client calls `add_entry` with `type`, `message`, and optional `module`/`issue`
- **THEN** the server inserts the entry into the unreleased section of `changelog.json` and returns `{"success": true}`

#### Scenario: get_unreleased tool
- **WHEN** an MCP client calls `get_unreleased`
- **THEN** the server returns the unreleased section from `changelog.json` as JSON

#### Scenario: get_version tool
- **WHEN** an MCP client calls `get_version` with an optional `format`
- **THEN** the server returns the formatted version from `version.yml`

#### Scenario: list_releases tool
- **WHEN** an MCP client calls `list_releases`
- **THEN** the server returns all published versions as an array

#### Scenario: create_release tool
- **WHEN** an MCP client calls `create_release` with `name` and `type`
- **THEN** the server executes the release logic (version bump + changelog update) and returns the new version

### Requirement: Package ships with .mcp.json.example
The package SHALL include a file `.mcp.json.example` in the package root with the configuration for the changelog MCP server. Developers copy it as `.mcp.json` into their project.

#### Scenario: .mcp.json.example present after installation
- **WHEN** the package is installed
- **THEN** `vendor/lightszentip/laravel-release-changelog-generator/.mcp.json.example` exists with a valid MCP configuration
