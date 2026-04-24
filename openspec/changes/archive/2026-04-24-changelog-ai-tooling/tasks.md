## 1. --json Flag in BaseCommand

- [x] 1.1 `--json` Option zur BaseCommand-Signatur hinzufügen
- [x] 1.2 `outputJson(array $data): int` Methode in BaseCommand implementieren
- [x] 1.3 `errorJson(string $message): int` Methode in BaseCommand für Fehlerfall
- [x] 1.4 Alle bestehenden Commands (add, release, set-release, update-version, show-version, generate-md) mit JSON-Output erweitern
- [x] 1.5 Tests für --json Flag in bestehenden Commands ergänzen

## 2. changelog:list Command

- [x] 2.1 `src/Commands/ListChangelog.php` erstellen (extends BaseCommand)
- [x] 2.2 Releases aus changelog.json lesen, nach Version sortieren, unreleased ausschließen
- [x] 2.3 Tabellarische Ausgabe (Version, Name, Datum, Anzahl Einträge)
- [x] 2.4 JSON-Output: Array mit `version`, `name`, `date`, `count`
- [x] 2.5 Command in ServiceProvider registrieren
- [x] 2.6 Tests für ListChangelog schreiben

## 3. changelog:show Command

- [x] 3.1 `src/Commands/ShowChangelog.php` erstellen (extends BaseCommand)
- [x] 3.2 `--ver=` Option: spezifische Version aus changelog.json laden (--version ist Symfony-reserviert, daher --ver)
- [x] 3.3 `--unreleased` Option: unreleased Section laden (Default wenn kein Flag)
- [x] 3.4 Fehlerbehandlung wenn Version nicht existiert
- [x] 3.5 JSON-Output: vollständiges Versions-Objekt
- [x] 3.6 Command in ServiceProvider registrieren
- [x] 3.7 Tests für ShowChangelog schreiben

## 4. changelog:suggest-release Command

- [x] 4.1 `src/Commands/SuggestRelease.php` erstellen (extends BaseCommand)
- [x] 4.2 Regellogik implementieren: unreleased Items iterieren, Typen-Mapping (breaking→major, feat/feature→minor, rest→patch)
- [x] 4.3 Textausgabe mit Empfehlung und Begründung
- [x] 4.4 JSON-Output: `{"type": "...", "reason": "..."}`
- [x] 4.5 Command in ServiceProvider registrieren
- [x] 4.6 Tests für alle Szenarien (nur fix, feat vorhanden, breaking vorhanden, leer, unbekannte Typen)

## 5. MCP Server

- [x] 5.1 `bin/changelog-mcp` PHP Script anlegen
- [x] 5.2 MCP JSON-RPC stdin/stdout Loop implementieren (initialize, tools/list, tools/call)
- [x] 5.3 Tool `add_entry` implementieren (schreibt direkt in changelog.json)
- [x] 5.4 Tool `get_unreleased` implementieren
- [x] 5.5 Tool `get_version` implementieren (liest version.yml, formatiert nach Template)
- [x] 5.6 Tool `list_releases` implementieren
- [x] 5.7 Tool `create_release` implementieren (Version bump + changelog update ohne Laravel)
- [x] 5.8 ENV-Variablen `CHANGELOG_PATH` und `VERSION_PATH` mit Defaults verdrahten
- [x] 5.9 Script in `composer.json` unter `bin` eintragen
- [x] 5.10 `.mcp.json.example` im Package-Root erstellen
- [x] 5.11 README-Abschnitt für MCP Server Setup ergänzen
