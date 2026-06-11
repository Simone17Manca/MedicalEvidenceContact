---
name: laravel-operation-guard
description: Laravel workflow guard for the MedicalEvidenceContact project. Use when Codex changes Laravel code, Blade views, routes, controllers, migrations, models, authentication, business profiles, Point of Contact management, job postings, applications, database behavior, or tests, and the user expects each operation to include PHPUnit coverage plus dry-run or non-destructive verification before completion.
---

# Laravel Operation Guard

## Core Rule

For every application operation, identify the affected behavior, make the smallest coherent change, add or update PHPUnit coverage, and run a dry-run or non-destructive verification before reporting completion.

Treat "operation" as one user-visible behavior, for example: register business, add Point of Contact, publish announcement, update announcement, delete announcement, accept job, view candidates, admin login.

## Project Workflow

1. Inspect the existing Laravel pattern first: routes, controller, model, Blade view, migration, and related tests.
2. If the operation touches database structure or real MySQL data, create or confirm a SQL backup before running migrations.
3. Implement the behavior in the narrowest files possible.
4. Add or update a PHPUnit feature test for the operation and any authorization boundary.
5. Run `scripts/dry-run.ps1` from this skill, passing a test filter that matches the changed feature.
6. If PHPUnit cannot run because the local DB or driver is blocked, still run PHP lint and Laravel route/migration dry-run checks, then report the blocker explicitly.

## Operation Checklist

- Route: named route exists and appears in `php artisan route:list`.
- Controller: validates input, checks role/ownership, redirects with status.
- Model: relationship or fillable/cast exists only when needed.
- View: form has CSRF, method spoofing for PUT/DELETE, validation errors, and business-only actions where required.
- PHPUnit: includes success path and at least one forbidden path for role/ownership-sensitive features.
- Dry-run: route list, migration pretend for DB changes, PHP lint for touched PHP files, targeted PHPUnit filter.

## Commands

Prefer the project wrappers on Windows:

```powershell
cmd /c artisan-local.cmd route:list --name=<route-prefix>
cmd /c artisan-local.cmd migrate --pretend
cmd /c artisan-local.cmd test --filter=<FeatureTestName>
tools\php-8.4\php.exe -c php-local.ini -l <file.php>
```

Use the bundled helper:

```powershell
powershell -ExecutionPolicy Bypass -File .codex\skills\laravel-operation-guard\scripts\dry-run.ps1 -TestFilter JobPostingTest -RouteName job-postings
```

## Local DB Notes

This project uses MySQL on `127.0.0.1:3308` through `mysql-dev.ini`. If MySQL is not running, a test command may hang. Prefer a short timeout and report the environment issue instead of pretending tests passed.

SQLite is not a reliable fallback unless `pdo_sqlite` is enabled in the local PHP runtime.

## References

Read `references/operations.md` when deciding what PHPUnit and dry-run checks fit a specific operation.
