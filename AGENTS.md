# AGENTS.md

This file provides guidance to AI coding agents when working with code in this repository.

## Commands

```bash
composer test              # Pest tests ausführen
composer test-coverage     # Tests mit Coverage-Report
composer analyse           # PHPStan Analyse (Level 4)
composer format            # Code-Style fixen mit Pint
```

Einzelnen Test ausführen:
```bash
vendor/bin/pest tests/Commands/AddChangelogTest.php
vendor/bin/pest --filter="test name"
```

## Architecture

This is a **Laravel Package** (nicht eine Applikation) – getestet via Orchestra Testbench.

### Core Workflow

1. `changelog:add` → schreibt in `resources/.changes/changelog.json` (unreleased section)
2. `changelog:release` → erhöht Version in `resources/.version/version.yml`, verschiebt unreleased → versioned entry in changelog.json
3. `changelog:generate-md` → rendert `CHANGELOG.md` via Blade Template

### Key Components

**`src/Logic/`** – Kernlogik:
- `VersionHandling` – liest/schreibt `version.yml`, inkrementiert Versionen; registriert als Singleton
- `VersionCalculator` – statische Methoden für Semver-Berechnungen (major/minor/patch/prerelease)
- `Version` – formatiert Versionstrings anhand konfigurierbarer Templates; registriert als Singleton unter `releasechangelog.version`

**`src/Commands/`** – Artisan Commands (alle unter `changelog:*`-Namespace)

**`src/Util/`**:
- `FileHandler` – zentrales Pfad-Management für alle Dateioperationen
- `VersionUtil` – Versions-Update-Logik nach Typ (patch/minor/major/rc/timestamp)
- `Constants` – appweite Konstanten

**`src/Data/ChangelogItem.php`** – DTO für einzelne Changelog-Einträge

**`src/ServiceProvider.php`** – registriert Commands, Singletons, Blade Directive (`@releasechangelog`), publiziert Assets

### Data Files

`resources/.version/version.yml` – Versionszustand:
```yaml
major: 1 / minor: 0 / patch: 1 / prerelease: rc / prereleasenumber: 0 / buildmetadata: null
```

`resources/.changes/changelog.json` – Changelog-Daten:
```json
{
  "unreleased": { "name": "tbd", "release": false, "feat": [...], "modules": [...] },
  "1.0.1.rc0":  { "name": "My First Release", "date": "...", "release": true, "feat": [...] }
}
```

`resources/views/changelog-md.blade.php` – Blade Template für CHANGELOG.md Generierung

### Configuration (`config/config.php`, Key: `releasechangelog`)

- `version_formats` – Named Templates mit Platzhaltern wie `{major}`, `{minor}`, `{patch}`, `{prerelease}`, `{timestamp}` usw.
- `prerelease` – ob Prerelease-Komponenten aktiv sind
- `blade-directive` – Name der Blade Directive (Default: `releasechangelog`)
- `markdown-path` – Ausgabepfad für CHANGELOG.md

### Module Support

Changelog-Einträge können Modulen zugeordnet werden (`--module=core`). In `changelog.json` landen diese unter `modules[].{type}[]` statt direkt unter dem Release-Eintrag.

### Testing

Tests laufen mit Pest v4 + Orchestra Testbench. `tests/TestCase.php` kopiert `version.yml`, `changelog.json` und Views vor jedem Test in ein temporäres Verzeichnis und räumt danach auf – Tests schreiben also nie in `resources/`.
