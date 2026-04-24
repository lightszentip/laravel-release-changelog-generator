## ADDED Requirements

### Requirement: MCP Server läuft als standalone PHP Script
Das Package SHALL ein ausführbares PHP Script unter `bin/changelog-mcp` mitliefern, das als MCP Server über stdio (JSON-RPC) kommuniziert. Das Script MUSS ohne Laravel-Bootstrap funktionieren und liest Dateipfade aus Umgebungsvariablen `CHANGELOG_PATH` und `VERSION_PATH`. Composer trägt das Script als `bin` ein.

#### Scenario: Script über vendor/bin aufrufbar
- **WHEN** `vendor/bin/changelog-mcp` ausgeführt wird
- **THEN** startet der MCP Server und wartet auf JSON-RPC Input über stdin

#### Scenario: Pfade via ENV konfigurierbar
- **WHEN** `CHANGELOG_PATH=/custom/path.json vendor/bin/changelog-mcp` ausgeführt wird
- **THEN** liest/schreibt der Server die angegebene Datei

### Requirement: MCP Server exponiert Changelog-Tools
Der MCP Server SHALL folgende Tools über das MCP-Protokoll anbieten: `add_entry`, `get_unreleased`, `get_version`, `list_releases`, `create_release`.

#### Scenario: add_entry Tool
- **WHEN** ein MCP-Client `add_entry` mit `type`, `message` und optionalem `module`/`issue` aufruft
- **THEN** fügt der Server den Eintrag in die unreleased Section von `changelog.json` ein und gibt `{"success": true}` zurück

#### Scenario: get_unreleased Tool
- **WHEN** ein MCP-Client `get_unreleased` aufruft
- **THEN** gibt der Server den unreleased Abschnitt aus `changelog.json` als JSON zurück

#### Scenario: get_version Tool
- **WHEN** ein MCP-Client `get_version` mit optionalem `format` aufruft
- **THEN** gibt der Server die formatierte Version aus `version.yml` zurück

#### Scenario: list_releases Tool
- **WHEN** ein MCP-Client `list_releases` aufruft
- **THEN** gibt der Server alle veröffentlichten Versionen als Array zurück

#### Scenario: create_release Tool
- **WHEN** ein MCP-Client `create_release` mit `name` und `type` aufruft
- **THEN** führt der Server die Release-Logik aus (Version bump + changelog update) und gibt die neue Version zurück

### Requirement: Package liefert .mcp.json.example mit
Das Package SHALL eine Datei `.mcp.json.example` im Package-Root mitliefern mit der Konfiguration für den changelog MCP Server. Entwickler kopieren sie als `.mcp.json` in ihr Projekt.

#### Scenario: .mcp.json.example vorhanden nach Installation
- **WHEN** das Package installiert ist
- **THEN** existiert `vendor/lightszentip/laravel-release-changelog-generator/.mcp.json.example` mit valider MCP-Konfiguration
