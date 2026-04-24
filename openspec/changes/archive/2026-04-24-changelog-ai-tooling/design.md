## Context

Das Package hat bereits eine solide Command-Struktur mit `BaseCommand`. Alle Commands lesen/schreiben `changelog.json` (als PHP-Arrays) und `version.yml`. Der MCP-Server muss dieselben Dateien direkt manipulieren ohne Laravel-Bootstrap, da AI-Tools den Server als lightweight subprocess starten.

## Goals / Non-Goals

**Goals:**
- `--json` Flag als einheitliches Muster für alle Commands
- Zwei neue Lesebefehle (`list`, `show`) die die bestehende JSON-Struktur nicht verändern
- `suggest-release` als reine Regellogik (kein API-Key nötig)
- MCP Server als standalone PHP Script, das direkt im Projektverzeichnis läuft

**Non-Goals:**
- Keine Änderung der `changelog.json` Struktur (consumer-kompatibel)
- Kein Laravel-Bootstrap im MCP Server
- Kein externer AI-API-Call für `suggest-release`
- Kein HTTP-Transport für MCP (nur stdio)

## Decisions

**`--json` in BaseCommand als Trait-Methode**
Alle Commands erben von `BaseCommand`. Eine `outputJson(array $data): int` Methode prüft ob `--json` gesetzt ist und gibt entweder JSON aus oder delegiert an die normale Ausgabe. Alternativen: eigene `JsonCommand` Basisklasse (zu viel Overhead), je Command selbst (Duplikation).

**`suggest-release` als reine Regellogik**
Typen-Mapping: `breaking` → major, `feat`/`feature` → minor, alles andere → patch. Wird über unreleased Items iteriert und der höchste Typ gewinnt. Kein externes Tool, deterministisch, pipeline-sicher.

**MCP Server als `bin/changelog-mcp` PHP Script**
Liest `CHANGELOG_PATH` und `VERSION_PATH` aus Umgebungsvariablen (Defaults: `resources/.changes/changelog.json`, `resources/.version/version.yml`). Implementiert MCP JSON-RPC über stdin/stdout in einer einfachen Loop. Composer `bin` Eintrag macht es via `vendor/bin/changelog-mcp` aufrufbar. Alternative (Artisan Command): ~300ms Laravel-Bootstrap-Overhead pro Tool-Call, inakzeptabel für interaktive AI-Tools.

**`.mcp.json.example` im Package-Root**
Wird nicht automatisch in Projekten platziert – der Entwickler kopiert es manuell als `.mcp.json`. Verhindert ungewolltes Überschreiben bestehender MCP-Konfigurationen.

## Risks / Trade-offs

`suggest-release` kennt nur registrierte Typen → unbekannte Typen (z.B. `chore`, `docs`) fallen auf `patch` zurück, was konservativ und sicher ist.

MCP Server liest Dateipfade aus ENV → wenn Projekt-Config (`releasechangelog.path`) vom Default abweicht, muss der Entwickler ENV in `.mcp.json` setzen. Mitigation: klare Dokumentation in `.mcp.json.example`.

`--json` Flag ändert Exit-Codes nicht → Fehler geben weiterhin non-zero zurück, JSON-Error-Objekt zusätzlich auf stdout.

## Open Questions

- Soll `changelog:show` ohne Argument standardmäßig `--unreleased` anzeigen oder eine Fehlermeldung ausgeben?
