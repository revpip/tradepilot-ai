# Changelog

All notable changes to TradePilot AI will be documented here.

## [0.6.0] - 2026-06-28

### Added

- LeadPilot v1.1.0 dynamic enquiry questions.
- Property type capture.
- Enquiry type capture.
- Preferred contact time capture.
- Photos-ready capture.
- Business notification email for new leads.
- Customer acknowledgement email for new leads.
- LeadPilot admin action handler foundation.

### Changed

- Bumped plugin version to 0.6.0.
- Updated LeadPilot module version to 1.1.0.
- Updated module registry to report LeadPilot v1.1.0.

## [0.5.0] - 2026-06-28

### Added

- LeadPilot v1.0.0 public lead funnel foundation.
- `[tradepilot_lead_form]` shortcode.
- Front-end service selector.
- Job description capture.
- Customer name, phone and email capture.
- Postcode capture.
- Budget range capture.
- Urgency capture.
- Consent checkbox.
- Public form styling.
- Lead submission handler.
- Lead saving into the custom leads table.
- Audit event when a new lead is created.
- LeadPilot admin lead list.
- LeadPilot admin lead detail screen.

### Changed

- Bumped plugin version to 0.5.0.
- Updated LeadPilot module version to 1.0.0.
- Updated module registry to report LeadPilot v1.0.0.

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
