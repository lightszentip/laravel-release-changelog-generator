## ADDED Requirements

### Requirement: changelog:suggest-release empfiehlt Version-Bump-Typ
Der Command `changelog:suggest-release` SHALL die unreleased Items aus `changelog.json` analysieren und den empfohlenen Version-Bump-Typ ausgeben. Regellogik: enthält unreleased `breaking` → `major`; enthält `feat` oder `feature` (aber kein `breaking`) → `minor`; sonst → `patch`. Der höchste zutreffende Typ gewinnt.

#### Scenario: Nur fixes vorhanden
- **WHEN** `changelog:suggest-release` ausgeführt wird und unreleased nur `fix`-Einträge enthält
- **THEN** gibt der Command `patch` als Empfehlung aus

#### Scenario: Features vorhanden
- **WHEN** `changelog:suggest-release` ausgeführt wird und unreleased `feat`-Einträge enthält
- **THEN** gibt der Command `minor` als Empfehlung aus

#### Scenario: Breaking changes vorhanden
- **WHEN** `changelog:suggest-release` ausgeführt wird und unreleased `breaking`-Einträge enthält
- **THEN** gibt der Command `major` als Empfehlung aus

#### Scenario: Unreleased leer
- **WHEN** `changelog:suggest-release` ausgeführt wird und unreleased keine Items enthält
- **THEN** gibt der Command `patch` als konservative Empfehlung aus

#### Scenario: Pipeline-Nutzung mit --json
- **WHEN** `changelog:suggest-release --json` ausgeführt wird
- **THEN** gibt der Command `{"type": "patch|minor|major", "reason": "<erklaerung>"}` aus, verwendbar mit `jq -r '.type'`

### Requirement: Unbekannte Typen fallen auf patch zurück
Typen die nicht in der Regellogik definiert sind (z.B. `chore`, `docs`, `refactor`) SHALL als `patch`-Kandidaten behandelt werden.

#### Scenario: Nur chore-Einträge
- **WHEN** unreleased nur `chore`-Einträge enthält
- **THEN** empfiehlt der Command `patch`
