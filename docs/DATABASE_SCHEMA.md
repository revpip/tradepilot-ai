# TradePilot AI Database Schema

## Version 0.4.0

The first database layer introduces two custom WordPress tables. All table names use the active WordPress database prefix.

---

## `{prefix}tradepilot_audit_log`

Stores system, settings, module and operational events.

| Column | Type | Purpose |
|---|---|---|
| id | BIGINT UNSIGNED | Primary key |
| event_type | VARCHAR(80) | Event category such as `settings_saved` |
| message | TEXT | Human-readable event message |
| context | LONGTEXT | JSON encoded structured event data |
| user_id | BIGINT UNSIGNED | WordPress user ID when available |
| ip_address | VARCHAR(45) | IPv4/IPv6 address where available |
| created_at | DATETIME | Event creation timestamp |

Indexes:

- `event_type`
- `user_id`
- `created_at`

---

## `{prefix}tradepilot_leads`

Skeleton lead table for LeadPilot and future AI scoring.

| Column | Type | Purpose |
|---|---|---|
| id | BIGINT UNSIGNED | Primary key |
| source | VARCHAR(80) | Website, Facebook, Google, WhatsApp, manual, etc. |
| status | VARCHAR(40) | new, contacted, quoted, booked, lost, archived |
| temperature | VARCHAR(20) | unscored, cold, warm, hot |
| score | TINYINT UNSIGNED | AI/commercial lead score 0–100 |
| service_type | VARCHAR(120) | Plumbing, bathroom, roofing, electrical, etc. |
| customer_name | VARCHAR(190) | Customer name |
| customer_email | VARCHAR(190) | Customer email |
| customer_phone | VARCHAR(80) | Customer phone |
| postcode | VARCHAR(20) | Job postcode |
| budget_range | VARCHAR(80) | Budget band |
| urgency | VARCHAR(80) | Urgency/timescale |
| description | LONGTEXT | Job description |
| meta | LONGTEXT | JSON encoded flexible metadata |
| assigned_to | BIGINT UNSIGNED | WordPress user/contractor ID later |
| created_at | DATETIME | Created timestamp |
| updated_at | DATETIME | Updated timestamp |

Indexes:

- `status`
- `temperature`
- `score`
- `service_type`
- `postcode`
- `created_at`
