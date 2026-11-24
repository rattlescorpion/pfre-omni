# Property ownership and Contacts 360° linkage

## Data contract

- A property may have multiple owners and an owner may own multiple properties.
- Ownership is represented by the first-class `property_ownerships` table, not a single owner-name column.
- Each record links an existing inventory property to one Contacts & Companies 360° directory entity.
- Ownership percentage is optional, decimal precision 2, and is not automatically forced to total 100%; partial, disputed, or unknown ownership can be recorded without inventing a value.
- `valid_from` and `valid_until` preserve historical ownership periods. Soft deletion archives a link without destroying its history.
- `ownership_type`, `document_reference`, notes and creator metadata provide provenance.

## Deliberate integration boundary

The current legacy inventory schema uses a numeric `properties.project_id` foreign key and does not expose a confirmed tenant/owner field on projects or properties. This migration therefore references the existing `properties` table and the new directory entities without inventing project ownership or bypassing access controls.

Before enabling create/edit/archive UI or project-level owner summaries, the inventory authorization policy must be mapped and tested. The UI must only offer properties the signed-in user is authorized to manage, and must reject directory entities outside that user's permitted scope.

## Next acceptance gates

1. Add policy-backed controller actions for listing, adding, updating and archiving ownership links.
2. Show ownership on both the property profile and person/company 360° profile.
3. Test multi-owner and multi-property cardinality, percentage validation, date ranges, history and cross-user access.
4. Confirm the legacy inventory project/property schema and tenant ownership model before adding project navigation or changing inventory tables.
