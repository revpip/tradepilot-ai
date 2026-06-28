# Build Notes

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

### Next Recommended Build

**TP-CORE-0.4.0 — Database, Settings Forms & Audit Persistence**

Planned:

- Custom database installer.
- Audit log table.
- Lead table skeleton.
- Nonce-protected settings form.
- Module enable/disable controls.
- Service area settings.
