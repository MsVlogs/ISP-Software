# ISP-Software Full-System QA & Security Audit

## Scope
- Application routing and authentication
- Database connection and error handling
- Dynamic SQL construction
- Network/MikroTik/OLT integration boundaries
- Destructive actions and authorization
- Production logging and repository hygiene
- Bilingual UI readiness and module consistency

## Confirmed findings
1. Historical `error_log` contains a fatal `prepare()` on a null connection and repeated invalid `foreach()` warnings.
2. `services/Database.php` already uses PDO exceptions, disables emulated prepares, and has a connection guard.
3. `services/Model.php` still has dynamic table/column/operator/value SQL construction in `getSingleData()`; identifiers are not allowlisted and values are interpolated directly.
4. `services/Model.php` exposes `raw_sql($raw_sql)`, which is a high-risk API if reachable from user-controlled input.
5. `index.php` routes pages from `$_GET['page']`; access control must be enforced by page/action authorization, not only by routing.
6. `.htaccess` currently contains only the PHP 8.1 handler and no explicit sensitive-file deny rules.

## QA gate
- [x] Repository reachable
- [x] `main` inspected
- [x] Security/QA branch created
- [x] DB configuration inspected
- [x] Error log inspected
- [x] Application entrypoint inspected
- [x] Dynamic SQL hotspots identified
- [ ] Full runtime regression: requires an executable application/database environment
- [ ] Authenticated destructive-action tests: requires runtime credentials/environment
- [ ] Network device integration tests: requires reachable test devices

## Release decision
**NOT YET PRODUCTION-HARDENED.** Static audit identified actionable DB/SQL/logging risks. Do not claim 100% security until runtime tests and remediation are completed.
