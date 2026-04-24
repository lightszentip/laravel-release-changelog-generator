## ADDED Requirements

### Requirement: Alle Commands unterstützen --json Flag
Alle Artisan Commands des Packages (add, release, set-release, update-version, show-version, generate-md, list, show, suggest-release) SHALL einen `--json` Flag akzeptieren. Bei gesetztem Flag gibt der Command einen JSON-String auf stdout aus anstelle von formatiertem Text. Der Exit-Code bleibt unverändert.

#### Scenario: Erfolgreicher Command mit --json
- **WHEN** ein Command mit `--json` ausgeführt wird und erfolgreich ist
- **THEN** gibt der Command einen validen JSON-String aus und beendet mit Exit-Code 0

#### Scenario: Fehlerhafter Command mit --json
- **WHEN** ein Command mit `--json` ausgeführt wird und fehlschlägt
- **THEN** gibt der Command `{"error": "<message>"}` auf stdout aus und beendet mit non-zero Exit-Code

### Requirement: JSON-Output ist maschinenlesbar strukturiert
Der JSON-Output eines Commands SHALL alle relevanten Ausgabedaten als Felder enthalten. Kein freier Text, keine ANSI-Farbcodes.

#### Scenario: show-version mit --json
- **WHEN** `changelog:show-version --json` ausgeführt wird
- **THEN** gibt der Command `{"version": "<string>"}` aus

#### Scenario: suggest-release mit --json für Pipeline
- **WHEN** `changelog:suggest-release --json` ausgeführt wird
- **THEN** gibt der Command `{"type": "patch|minor|major", "reason": "<string>"}` aus
