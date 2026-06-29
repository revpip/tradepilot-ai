# Revision Log

## Build REPLYPILOT-1.0.0

**Date:** 2026-06-28  
**Build Name:** Message Templates & Follow-Up Engine  
**Repository:** revpip/tradepilot-ai

### Purpose

Introduce ReplyPilot as the first messaging layer so leads can receive automatic acknowledgements and admins can manually send templated follow-ups from the lead detail screen.

### Added

- ReplyPilot module.
- Default message templates.
- Merge-tag rendering for lead fields.
- Automatic customer acknowledgement after lead creation.
- Manual template sender on lead detail screens.
- ReplyPilot message history stored in lead metadata.
- ReplyPilot audit events.

### Changed

- Plugin version bumped to 1.1.0.
- ReplyPilot registered as an enabled module.
- Lead detail screens now include ReplyPilot controls.

### Next Build Target

**REPLYPILOT-1.1.0 — Editable Templates & Follow-Up Scheduling Foundation**

---

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
