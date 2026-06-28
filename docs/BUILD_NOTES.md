# Build Notes

## TP-CORE-0.4.0 — Database, Settings Forms & Audit Persistence

### Summary

This build gives TradePilot AI its first durable operational layer: custom database tables, nonce-protected settings forms, module toggles and database-backed audit logging.

### Added

- Custom database installer.
- Audit log table.
- Lead table skeleton.
- Database schema documentation.
- Security helper class.
- Nonce-protected settings save handler.
- Module enable/disable controls.
- Service area settings textarea.
- AI threshold settings.
- Database-backed audit event recording.
- Logs screen showing recent audit events.
- Automatic version upgrade runner.

### Notes

This build prepares the system for LeadPilot by creating the lead data structure before the public enquiry funnel is introduced.

### Next Recommended Build

**LEADPILOT-1.0.0 — Smart Lead Funnel Foundation**

Planned:

- Front-end lead shortcode.
- Service selector.
- Dynamic enquiry fields.
- Customer details capture.
- Postcode/service area capture.
- Budget and urgency capture.
- Save lead to custom table.
- Admin lead list and lead detail view.

---

## TP-CORE-0.3.0 — Admin Framework & Module Loader

### Summary

This build creates the first usable WordPress admin framework for TradePilot AI and introduces the module registry that future intelligence systems will use.

### Added

- Admin menu and submenu shell.
- Dashboard card layout.
- Module manager screen.
- Settings shell.
- Logs shell.
- Module registry.
- Active module loader.
- Placeholder module files.
- Audit log interface.
- Settings accessor interface.
- Admin CSS.
- Future architecture directories for AI, database, security and templates.

### Notes

The module manager currently displays module status. Toggle controls will be added in the next core settings build once nonce-protected settings forms are introduced.
