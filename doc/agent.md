# Agent Instructions for Using the Laravel Release Changelog Generator

## Purpose
This document provides guidance for agents (human or automated) on how to use the Laravel Release Changelog Generator plugin to manage and automate changelog entries in your Laravel projects.

## Usage Overview
The plugin provides CLI commands to add, generate, and manage changelog entries in a structured and automated way. It supports grouping changelog items by module and type, and integrates with standard Laravel workflows.

## Key Commands

### 1. Add a Changelog Entry
Use the following command to add a new changelog entry:

```
php artisan changelog:add --type=<type> --message="<description>" [--issue=<issue>] [--module=<module>]
```
- `--type` (required): The type of change (e.g., added, fixed, changed, removed).
- `--message` (required): A short description of the change.
- `--issue` (optional): Reference to an issue or ticket number.
- `--module` (optional): The module or component the change belongs to. If omitted, the entry is added directly under the type.

### 2. Generate the Changelog Markdown
To generate or update the `CHANGELOG.md` file from the current changelog data:

```
php artisan changelog:generate-md
```

### 3. Set a Release
To mark the current unreleased changes as a new release:

```
php artisan changelog:set-release --version=<version> --date=<YYYY-MM-DD>
```

### 4. Show the Current Version
To display the current version as tracked by the changelog:

```
php artisan changelog:show-version
```

## Best Practices
- Always provide a clear and concise message for each changelog entry.
- Use modules to keep large projects organized.
- Run code quality and static analysis tools (see `develop.md`) before releasing.
- Keep the changelog up to date with every pull request or feature/bugfix merge.

## Troubleshooting
- If you encounter errors, check the structure of your changelog file and ensure all required arguments are provided.
- For more details on available commands and options, use `php artisan list` or `php artisan help <command>`.

## Additional Resources
- Review the `README.md` for general project information.

---
This document is intended for all agents interacting with the changelog generator, including CI/CD bots, developers, and release managers.

