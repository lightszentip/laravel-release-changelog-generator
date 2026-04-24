## Context

The package already has a solid command structure with `BaseCommand`. All commands read/write `changelog.json` (as PHP arrays) and `version.yml`. The MCP server must manipulate the same files directly without a Laravel bootstrap, since AI tools start the server as a lightweight subprocess.

## Goals / Non-Goals

**Goals:**
- `--json` flag as a consistent pattern across all commands
- Two new read commands (`list`, `show`) that do not modify the existing JSON structure
- `suggest-release` as pure rule logic (no API key required)
- MCP server as a standalone PHP script running directly in the project directory

**Non-Goals:**
- No change to the `changelog.json` structure (consumer-compatible)
- No Laravel bootstrap in the MCP server
- No external AI API call for `suggest-release`
- No HTTP transport for MCP (stdio only)

## Decisions

**`--json` in BaseCommand as a trait method**
All commands inherit from `BaseCommand`. An `outputJson(array $data): int` method checks whether `--json` is set and either outputs JSON or delegates to normal output. Alternatives: a dedicated `JsonCommand` base class (too much overhead), per-command implementation (duplication).

**`suggest-release` as pure rule logic**
Type mapping: `breaking` → major, `feat`/`feature` → minor, everything else → patch. Iterates over unreleased items and the highest type wins. No external tool, deterministic, pipeline-safe.

**MCP server as `bin/changelog-mcp` PHP script**
Reads `CHANGELOG_PATH` and `VERSION_PATH` from environment variables (defaults: `resources/.changes/changelog.json`, `resources/.version/version.yml`). Implements MCP JSON-RPC over stdin/stdout in a simple loop. Composer `bin` entry makes it callable via `vendor/bin/changelog-mcp`. Alternative (Artisan command): ~300ms Laravel bootstrap overhead per tool call, unacceptable for interactive AI tools.

**`.mcp.json.example` in the package root**
Not automatically placed in consumer projects — the developer copies it manually as `.mcp.json`. Prevents unintentional overwriting of existing MCP configurations.

## Risks / Trade-offs

`suggest-release` only knows registered types → unknown types (e.g. `chore`, `docs`) fall back to `patch`, which is conservative and safe.

MCP server reads file paths from ENV → if the project config (`releasechangelog.path`) deviates from the default, the developer must set ENV in `.mcp.json`. Mitigation: clear documentation in `.mcp.json.example`.

`--json` flag does not change exit codes → errors still return non-zero, with a JSON error object additionally on stdout.

## Open Questions

- Should `changelog:show` without an argument default to `--unreleased` or return an error?
