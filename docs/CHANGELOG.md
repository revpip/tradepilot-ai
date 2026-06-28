# Changelog

All notable changes to TradePilot AI will be documented here.

## [0.4.0] - 2026-06-28

### Added

- Custom database installer.
- Database schema documentation.
- Audit log table.
- Lead table skeleton.
- Security helper class for nonce/capability checks and admin redirects.
- Nonce-protected settings save handler.
- Module enable/disable controls.
- Service area settings.
- AI scoring threshold settings.
- Database-backed audit event recording.
- Logs screen showing recent audit events.
- Automatic upgrade runner for future database changes.

### Changed

- Bumped plugin version to 0.4.0.
- Updated activator to install custom database tables.
- Updated core loader to include database and security layers.
- Updated admin settings screen from placeholder cards to a real form.
- Updated module manager from status display to configurable toggles.

## [0.3.0] - 2026-06-28

### Added

- Admin dashboard framework.
- TradePilot AI admin menu and submenus.
- Dashboard cards for leads, quotes, jobs, revenue and active modules.
- Module registry.
- Active module loader.
- Module manager screen.
- Settings shell.
- Logs shell.
- Audit log interface stub.
- Settings accessor interface stub.
- Module placeholder files for LeadPilot, QuotePilot, RoutePilot, ReplyPilot, ReviewPilot, InsightPilot and FranchisePilot.
- Admin CSS.
- Future architecture directories for AI, database, security and templates.

### Changed

- Bumped plugin version to 0.3.0.
- Updated core loader to include settings and audit frameworks.
- Updated activator to store module registry and default settings.

## [0.1.0] - 2026-06-28

### Added

- Repository foundation.
- Product README.
- Master roadmap.
- Modular product naming structure.
- Initial build documentation standard.
