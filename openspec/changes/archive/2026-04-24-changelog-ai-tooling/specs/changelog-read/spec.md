## ADDED Requirements

### Requirement: changelog:list zeigt alle Releases
Der Command `changelog:list` SHALL alle veröffentlichten Versionen aus `changelog.json` aufgelistet ausgeben, sortiert absteigend nach Version. Unreleased wird nicht angezeigt. Jede Zeile enthält: Version, Release-Name, Datum, Anzahl Einträge.

#### Scenario: Releases vorhanden
- **WHEN** `changelog:list` ausgeführt wird und Releases existieren
- **THEN** gibt der Command eine Zeile pro Release aus, neueste zuerst

#### Scenario: Keine Releases vorhanden
- **WHEN** `changelog:list` ausgeführt wird und nur unreleased existiert
- **THEN** gibt der Command eine leere Liste aus und beendet mit Exit-Code 0

#### Scenario: --json Flag
- **WHEN** `changelog:list --json` ausgeführt wird
- **THEN** gibt der Command ein JSON-Array aus: `[{"version": "...", "name": "...", "date": "...", "count": 5}]`

### Requirement: changelog:show zeigt Einträge einer Version
Der Command `changelog:show` SHALL die Einträge einer spezifischen Version oder von unreleased ausgeben. Mit `--version=<x.y.z>` wird diese Version angezeigt. Mit `--unreleased` werden die aktuellen unreleased Items angezeigt. Ohne Argument SOLL `--unreleased` als Default gelten.

#### Scenario: Unreleased anzeigen
- **WHEN** `changelog:show --unreleased` ausgeführt wird
- **THEN** gibt der Command alle unreleased Items gruppiert nach Typ aus

#### Scenario: Spezifische Version anzeigen
- **WHEN** `changelog:show --version=1.1.0` ausgeführt wird und diese Version existiert
- **THEN** gibt der Command die Items dieser Version aus

#### Scenario: Version nicht gefunden
- **WHEN** `changelog:show --version=9.9.9` ausgeführt wird und diese Version nicht existiert
- **THEN** gibt der Command eine Fehlermeldung aus und beendet mit non-zero Exit-Code

#### Scenario: --json Flag
- **WHEN** `changelog:show --unreleased --json` ausgeführt wird
- **THEN** gibt der Command das unreleased-Objekt als JSON aus
