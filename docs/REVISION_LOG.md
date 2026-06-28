# Revision Log

## Build LEADPILOT-1.2.0

**Date:** 2026-06-28  
**Build Name:** Status Persistence & Photo Upload Foundation  
**Repository:** revpip/tradepilot-ai

### Purpose

Add persistent status helper support and allow customers to attach job photos during enquiry submission.

### Added

- LeadPilot upload helper.
- Front-end image upload field.
- Multiple image selection.
- JPG, PNG and WEBP checks.
- Upload metadata attached to lead submissions.
- LeadPilot status persistence helper.

### Changed

- Plugin version bumped to 0.7.0.
- LeadPilot module version bumped to 1.2.0.
- Module registry now reports LeadPilot v1.2.0.

### Next Build Target

**LEADPILOT-1.3.0 — Admin Photo Preview & Lead Action Refinement**

---

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
