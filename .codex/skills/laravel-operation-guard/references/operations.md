# Operation Matrix

Use this matrix to pick verification for common MedicalEvidenceContact changes.

## Authentication and Registration

- Tests: `RegistrationTest`, `AuthenticationTest`, `AdminRegistrationTest`.
- Dry-run: PHP lint `app/Actions/Fortify/CreateNewUser.php`, auth controllers, `routes/web.php`.
- Route check: `route:list --name=admin` or `route:list --name=register` when routes changed.

## Business Profile and Point of Contact

- Tests: `BusinessProfileTest`.
- Required coverage: business can perform the operation; professional/admin cannot when business-only.
- Dry-run: `route:list --name=business-points-of-contact`, PHP lint controller and test files.

## Job Postings

- Tests: `JobPostingTest`.
- Required coverage: create, show, update, delete, owner-only authorization, professional visibility.
- Dry-run: `route:list --name=job-postings`, PHP lint `JobPostingController.php`, routes, tests.

## Job Applications

- Tests: `JobPostingTest` or a dedicated `JobApplicationTest`.
- Required coverage: professional can apply, duplicate applications are blocked by DB or validation, business cannot apply.
- Dry-run: `route:list --name=job-applications`.

## Database Migrations

- Always create or confirm a SQL backup before changing real MySQL schema.
- Run `cmd /c artisan-local.cmd migrate --pretend` before applying.
- After applying, verify with `DESCRIBE <table>` or `SHOW TABLES LIKE '<pattern>'`.

## Blade UI Changes

- PHP lint cannot validate Blade fully, so combine route rendering tests with targeted feature tests.
- For business-only links, assert visible for business and not reachable for professional through controller authorization.
