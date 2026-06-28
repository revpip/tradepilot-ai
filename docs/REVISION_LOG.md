# Revision Log

## Build LEADPILOT-1.1.0

**Date:** 2026-06-28  
**Build Name:** Dynamic Questions & Notifications  
**Repository:** revpip/tradepilot-ai

### Purpose

Enhance the public lead funnel so enquiries collect richer information and automatically notify both the business and the customer after submission.

### Added

- Property type question.
- Enquiry type question.
- Preferred contact time question.
- Photos-ready question.
- Business notification email.
- Customer acknowledgement email.
- LeadPilot notification class.
- LeadPilot admin action handler foundation.

### Changed

- Plugin version bumped to 0.6.0.
- LeadPilot module version bumped to 1.1.0.
- Module registry now reports LeadPilot v1.1.0.

### Deferred

- Persistent lead status editing.
- Full admin notes timeline.
- File/photo upload handling.
- AI lead scoring.

### Next Build Target

**LEADPILOT-1.2.0 — Status Persistence, Admin Notes & Photo Uploads**

Planned:

- Lead status saved into lead table.
- Admin notes stored in lead metadata.
- Photo upload support.
- Admin photo preview.
- Safer upload validation.

---

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
