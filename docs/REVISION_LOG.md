# Revision Log

## Build LEADPILOT-1.0.0

**Date:** 2026-06-28  
**Build Name:** Smart Lead Funnel Foundation  
**Repository:** revpip/tradepilot-ai

### Purpose

Turn LeadPilot from a placeholder into a working lead capture system that can receive website enquiries, save them to the custom lead table and display them inside the WordPress admin area.

### Added

- LeadPilot public shortcode: `[tradepilot_lead_form]`.
- Service selector.
- Job description field.
- Postcode field.
- Budget range selector.
- Urgency selector.
- Customer name, phone and email fields.
- Consent checkbox.
- Public form styling.
- Nonce-protected public form submission.
- Lead saving into `{prefix}tradepilot_leads`.
- Lead audit event recording.
- LeadPilot data layer.
- LeadPilot admin lead list.
- LeadPilot admin lead detail screen.
- LeadPilot menu callback for the TradePilot AI Leads screen.

### Changed

- Plugin version bumped to 0.5.0.
- LeadPilot module version bumped to 1.0.0.
- Module registry now reports LeadPilot v1.0.0.

### Deferred

- Photo upload handling.
- Dynamic trade-specific question branching.
- AI lead scoring.
- Lead status editing.
- Email/customer notifications.

### Next Build Target

**LEADPILOT-1.1.0 — Dynamic Questions, Lead Statuses & Notifications**

Planned:

- Dynamic questions by service type.
- Lead status update controls.
- Admin notes.
- Email notification to business.
- Customer acknowledgement email.
- Service-area matching.

---

## Build TP-CORE-0.4.0

**Date:** 2026-06-28  
**Build Name:** Database, Settings Forms & Audit Persistence  
**Repository:** revpip/tradepilot-ai

### Purpose

Add the first durable platform layer so TradePilot AI can safely store leads, persist audit events, save settings and manage modules through protected admin forms.

### Added

- Custom database installer.
- Audit log custom table.
- Lead custom table skeleton.
- Database schema documentation.
- Security helper class.
- Nonce-protected settings save handler.
- Nonce-protected module save handler.
- Module enable/disable checkboxes.
- General settings form.
- Service area settings.
- AI threshold settings.
- Database-backed audit logging.
- Recent audit events table on the Logs screen.
- Automatic upgrade runner when plugin version changes.

### Changed

- Plugin version bumped to 0.4.0.
- Activator now installs database tables.
- Core loader now loads database and security layers.
- Settings screen now saves real values.
- Module manager now saves enabled/disabled states.
- Dashboard new lead count now reads from the lead table.

---

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
