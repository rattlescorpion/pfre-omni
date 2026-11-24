# Property and Project Schema Reconciliation

**Status:** assessment complete; canonicalization is intentionally not applied yet.

## Why this is required

The property inventory currently has multiple competing contracts. They cannot safely be treated as interchangeable.

| Layer | Current contract |
|---|---|
| Initial database migration | `project_id`, `unit_number`, `carpet_area`, `base_price`, `type`, `status`, `features` |
| Eloquent `Property` model | PFRE inventory fields such as `prop_no`, `listing_type`, `property_type`, `carpet_sqft`, `asking_price`, `monthly_rent`, `building_name`, `society_name`, `created_by`, `assigned_to` |
| CRM repository | `name`, `price`, `area_sqft`, `description`, `commission_rate` and other fields not present in the initial migration |
| Store request and create form | Validates and submits the CRM repository contract |
| Property controller | Only implements index/create/store, while the resource route advertises show/edit/update/destroy |
| Inventory routes | Prefixes paths with `/inventory`, while property views and redirects use `/properties` |

The project migration defines `name`, `maharera_reg_no`, `location_area`, `address`, and `status`. The initial property migration requires a project, so a property cannot be inserted using the current form without a project relationship.

## Changes already made to support ownership

Migration `2026_11_24_000006_add_ownership_authorization_fields_to_properties_table.php` adds nullable `created_by`, `assigned_to`, and `deleted_at` to the existing legacy properties table. This is an additive migration and does not rename or delete legacy columns.

The ownership service intentionally limits access to the property creator or assigned user and the directory entity owner. This is a temporary authorization boundary until a formal tenant/organization policy is adopted.

## Canonical direction

Use the PFRE inventory vocabulary as the canonical persistence contract, because it captures the real estate-specific fields required by the business:

- Project and unit identity: project, building/society, wing, floor, flat/unit, property number.
- Listing and classification: sale/rent, residential/commercial/plot, configuration, possession and status.
- Area and pricing: carpet/built-up/super-built-up/plot area, asking price, rent, deposit, maintenance.
- Location: structured address, locality, PIN code, coordinates, CTS/survey/hissa and RERA references.
- Relationships: owners through `property_ownerships`, not duplicated owner name/phone/email as the only ownership source.
- Governance: creator, assignee, soft deletion, and later tenant/organization scope.

The CRM request/repository layer should be adapted to this contract. It should not create a second set of generic columns such as `name`, `price`, and `area_sqft` just to satisfy a form.

## Safe implementation sequence

1. Confirm the deployed database migration history and whether any production data uses the legacy schema.
2. Add an additive migration for any missing PFRE inventory columns; do not rewrite old migrations.
3. Introduce a dedicated FormRequest that validates canonical PFRE fields and a mapper that translates request data to the existing schema.
4. Replace generic repository field lists with the canonical model contract, including project validation and ownership relations.
5. Implement resource actions that are actually supported; until then, restrict routes rather than advertise missing controller methods.
6. Align all links, redirects, and forms with named `inventory.properties.*` routes.
7. Add feature tests for property creation, detail, update, archive, project ownership, and cross-user authorization.
8. Run migrations only after confirming the intended staging/production service and taking a verified backup.

## Deployment boundary

The known Render preview service is attached to the website repository, not this CRM repository. Do not deploy PFRE-Omni commits to that service. No live database migration should be run until a dedicated CRM service/database is positively identified and backed up.
