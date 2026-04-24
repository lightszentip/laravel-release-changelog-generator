## ADDED Requirements

### Requirement: changelog:suggest-release recommends a version bump type
The command `changelog:suggest-release` SHALL analyse the unreleased items from `changelog.json` and output the recommended version bump type. Rule logic: if unreleased contains `breaking` → `major`; if it contains `feat` or `feature` (but no `breaking`) → `minor`; otherwise → `patch`. The highest matching type wins.

#### Scenario: Only fixes present
- **WHEN** `changelog:suggest-release` is executed and unreleased contains only `fix` entries
- **THEN** the command outputs `patch` as the recommendation

#### Scenario: Features present
- **WHEN** `changelog:suggest-release` is executed and unreleased contains `feat` entries
- **THEN** the command outputs `minor` as the recommendation

#### Scenario: Breaking changes present
- **WHEN** `changelog:suggest-release` is executed and unreleased contains `breaking` entries
- **THEN** the command outputs `major` as the recommendation

#### Scenario: Unreleased empty
- **WHEN** `changelog:suggest-release` is executed and unreleased contains no items
- **THEN** the command outputs `patch` as a conservative recommendation

#### Scenario: Pipeline usage with --json
- **WHEN** `changelog:suggest-release --json` is executed
- **THEN** the command outputs `{"type": "patch|minor|major", "reason": "<explanation>"}`, usable with `jq -r '.type'`

### Requirement: Unknown types fall back to patch
Types not defined in the rule logic (e.g. `chore`, `docs`, `refactor`) SHALL be treated as `patch` candidates.

#### Scenario: Only chore entries
- **WHEN** unreleased contains only `chore` entries
- **THEN** the command recommends `patch`
