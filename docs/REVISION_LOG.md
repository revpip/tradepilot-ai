# Revision Log

## Build LEADPILOT-AI-1.0.0

**Date:** 2026-06-28  
**Build Name:** Lead Scoring & Smart Summaries  
**Repository:** revpip/tradepilot-ai

### Purpose

Introduce the first intelligence layer for LeadPilot so new enquiries are automatically scored, classified and given a suggested next action.

### Added

- LeadPilot AI module.
- Rule-based scoring engine.
- Hot/warm/cold temperature classification.
- Smart lead summary generation.
- Recommended next action generation.
- Post-lead-create scoring hook.
- AI score and temperature saved to the lead table.
- AI metadata saved with the lead.
- AI score shown on the lead list.
- AI summary panel shown on lead detail screens.

### Changed

- Plugin version bumped to 1.0.0.
- LeadPilot now fires a scoring hook after lead creation.
- LeadPilot AI registered as a standalone enabled module.

### Next Build Target

**REPLYPILOT-1.0.0 — Message Templates & Follow-Up Engine**

---

## Build LEADPILOT-1.4.0

**Date:** 2026-06-28  
**Build Name:** Lead Filters & Admin Workflow Polish  
**Repository:** revpip/tradepilot-ai

### Purpose

Improve lead management by adding list filters and clearer lead detail information for operational use.

### Added

- Status filter on the LeadPilot lead list.
- Service filter on the LeadPilot lead list.
- Urgency filter on the LeadPilot lead list.
- Extra answer display on lead detail screens.
- Admin note timeline display.
- Filter bar styling.
- Status badge styling.
- Note timeline styling.
- Lead metadata helper.
- Filtered lead query support.
