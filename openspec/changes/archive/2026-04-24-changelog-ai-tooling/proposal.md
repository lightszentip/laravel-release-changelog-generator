## Why

AI developer tools (Claude Code, Cursor) und CI/CD-Pipelines können den Changelog aktuell nicht direkt nutzen – die CLI-Ausgaben sind nur für Menschen lesbar und es fehlen Lesebefehle sowie ein MCP-Interface. Da Changelogs during development auf feature branches gepflegt und in Pipelines released werden, müssen beide Workflows maschinenfreundlich sein.

## What Changes

- `--json` Flag für alle Artisan Commands: gibt strukturierten JSON-Output statt formatiertem Text
- `changelog:list` Command: listet alle Releases (Version, Name, Datum, Anzahl Einträge)
- `changelog:show` Command: zeigt Einträge einer Version (`--version=1.1.0`) oder unreleased (`--unreleased`)
- `changelog:suggest-release` Command: leitet empfohlenen Version-Bump aus unreleased Items ab (feat→minor, fix/chore→patch, breaking→major)
- MCP Server (`vendor/bin/changelog-mcp`): standalone PHP Script, spricht MCP JSON-RPC über stdio ohne Laravel-Bootstrap; Package liefert `.mcp.json.example` mit

## Capabilities

### New Capabilities

- `json-output`: `--json` Flag auf allen Commands für maschinenlesbaren Output
- `changelog-read`: Neue Lesebefehle `changelog:list` und `changelog:show`
- `suggest-release`: Automatische Empfehlung des Version-Bump-Typs aus unreleased Items
- `mcp-server`: Standalone MCP Server für native AI-Tool-Integration

### Modified Capabilities

## Impact

- `src/Commands/` – alle bestehenden Commands erhalten `--json` Support; drei neue Command-Klassen
- `src/Commands/BaseCommand.php` – JSON-Output-Logik als Basis-Methode
- `bin/changelog-mcp` – neues standalone PHP Script (kein Laravel-Dependency)
- `.mcp.json.example` – neue Datei im Package-Root
- `composer.json` – `bin/changelog-mcp` als composer bin eintragen
- Keine Änderung an `changelog.json` Struktur (consumer-kompatibel)
