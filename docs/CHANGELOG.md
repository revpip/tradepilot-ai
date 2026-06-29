# Changelog

All notable changes to TradePilot AI will be documented here.

## [1.1.0] - 2026-06-28

### Added

- ReplyPilot v1.0.0 module.
- Default customer message templates.
- Merge-tag rendering for lead fields.
- Automatic customer acknowledgement after new lead creation.
- Manual template send controls on lead detail screens.
- ReplyPilot message history stored in lead metadata.
- ReplyPilot audit log events.

### Changed

- Bumped plugin version to 1.1.0.
- Registered ReplyPilot as an enabled module.
- Lead detail screens now include a ReplyPilot messages panel.

## [1.0.0] - 2026-06-28

### Added

- LeadPilot AI v1.0.0 module.
- Rule-based lead scoring engine.
- Hot/warm/cold lead temperature classification.
- Smart lead summaries.
- Recommended next actions.
- Lead scoring triggered after new lead creation.
- AI score and temperature saved to the lead table.
- AI summary and next action saved into lead metadata.
- AI score column added to the lead list.
- AI summary panel added to lead detail screens.

### Changed

- Bumped plugin version to 1.0.0.
- Registered LeadPilot AI as its own module.
- LeadPilot submission now fires a post-create scoring hook.

## [0.9.0] - 2026-06-28

### Added

- LeadPilot v1.4.0 lead filters.
- Status, service and urgency filters on the lead list.
- Extra answer display on lead detail screens.
- Admin note timeline display.
- Lead filter, status badge and note styling.
- Lead metadata helper and filtered query support.
