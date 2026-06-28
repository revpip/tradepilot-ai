# Revision Log

## Build TP-CORE-0.3.0

**Date:** 2026-06-28  
**Build Name:** Admin Framework & Module Loader  
**Repository:** revpip/tradepilot-ai

### Purpose

Create the first usable WordPress admin framework and module registry so TradePilot AI can grow cleanly through independent intelligence modules.

### Added

- Admin dashboard shell.
- Admin menu and submenu structure.
- Dashboard summary cards.
- Module registry.
- Active module loader.
- Module manager screen.
- Settings shell.
- Logs shell.
- Audit log interface stub.
- Settings accessor interface stub.
- Admin stylesheet.
- Module placeholders for LeadPilot, QuotePilot, RoutePilot, ReplyPilot, ReviewPilot, InsightPilot and FranchisePilot.
- Architecture folders for AI, database, security and templates.

### Changed

- Plugin version bumped to 0.3.0.
- Core loader now loads module, settings and audit frameworks.
- Activator now persists module registry and default settings.

### Deferred

- Module enable/disable form controls.
- Persistent database-backed audit log.
- Custom lead database tables.
- Nonce-protected settings save actions.

### Next Build Target

**TP-CORE-0.4.0 — Database, Settings Forms & Audit Persistence**

Planned:

- Custom database installer.
- Audit log table.
- Lead table skeleton.
- Settings save handlers.
- Module enable/disable controls.
- Service area settings.

---

## Build TP-AF-0.1.0

**Date:** 2026-06-28  
**Build Name:** Architecture Foundation  
**Repository:** revpip/tradepilot-ai

### Purpose

Create the permanent foundation for TradePilot AI as a structured, modular, WordPress-first platform that can later support local operations, franchise scaling, and SaaS licensing.

### Added

- Initial README.
- Master product roadmap.
- Changelog.
- Revision log.
- Core module naming system.
- Phase-based build plan.

### Core Module Names Locked

- TradePilot Core™
- LeadPilot™
- QuotePilot™
- RoutePilot™
- ReplyPilot™
- ReviewPilot™
- InsightPilot™
- FranchisePilot™

### Development Rules

- Each build must update this revision log.
- Each build must update the changelog.
- Every module must use clear file headers.
- Every major function must be marked with module, purpose, and version.
- Security checks must be applied before admin, database, file upload, and API operations.
