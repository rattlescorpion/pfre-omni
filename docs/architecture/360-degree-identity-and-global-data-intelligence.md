# 360° Identity & Global Data Intelligence — Architecture Specification

**Status:** Design specification only  
**Scope:** Contacts 360°, Company 360°, shared identity data, and Global Data Intelligence Engine  
**Database impact:** None. This document does not authorize migrations, schema changes, data imports, or production writes.

## 1. Architectural decisions to preserve

The following decisions are approved and must remain invariant in subsequent design and implementation:

1. Mobile numbers and email addresses are reusable shared communication identities. Their assignments must be represented independently from a single contact or company record so that a channel can be associated with multiple people, companies, branches, or roles where the relationship is valid.
2. Person and organisation names use a global reusable name dictionary. The dictionary is a controlled reference vocabulary, not a replacement for each entity's legally recorded name.
3. Data ownership is global and explicit. Every governed record and relationship must have an identifiable owning tenant/organisation or platform-level authority, with access governed separately from ownership.
4. Contacts 360° and Company 360° are linked views over canonical records and relationships. They must not create duplicate copies of the same person, company, mobile number, or email merely to populate a profile.
5. This phase is specification-only. Do not run migrations, modify live databases, or alter existing production data until a separately approved implementation plan and migration review exist.

## 2. Core domain model

### 2.1 Canonical entities

- **Person:** A natural person with a stable internal identity, legal-name components, aliases, lifecycle status, and audit history.
- **Organisation:** A legal entity or other recognised organisation. Keep legal identity distinct from its trading names and branches.
- **Organisation branch:** A location or operational unit belonging to an organisation. A branch is not a separate legal entity unless explicitly modelled as one.
- **Communication point:** A normalised mobile/telephone number or email address. Store the channel once; attach it to entities through assignment records.
- **Name dictionary entry:** A reusable, language-aware reference entry with canonical form, alternate spellings, script, category, and lifecycle status.
- **Relationship:** A dated, typed, auditable link between two canonical entities, optionally scoped to an organisation, branch, project, property, or role.
- **Address:** A reusable structured postal/location record, with entity-to-address assignments and address-purpose metadata.
- **Identity document:** A separately permissioned document identifier linked to a person or organisation. Multiple document types and multiple identifiers per entity must be supported; sensitive values must not be exposed in general profile responses.

### 2.2 Relationship principles

- One person may be associated with many organisations and branches.
- One organisation may be associated with many people in different roles.
- One person may have multiple communication points; one communication point may have multiple valid assignments.
- A relationship must carry its type, validity period, verification state, source, and audit metadata.
- Do not infer ownership, directorship, employment, or legal authority merely from a shared phone number or email.
- Support historical relationships without overwriting the current state.

## 3. Contacts 360° profile

The Contacts 360° screen is a unified profile for a person. Its sections should be permission-aware and progressively loadable:

1. **Overview:** Display name, profile status, primary verified communication points, preferred contact method, and key relationship summary.
2. **Identity:** First, middle, last, and other legal-name components; aliases; name pronunciation/transliteration where supplied; date of birth only where authorised.
3. **Communication:** Mobile, landline, email, messaging handles, assignment purpose, primary flag, verification status, consent, and validity dates.
4. **Addresses:** Current and historical addresses, address purpose, structured locality/postal fields, and source/verification.
5. **Organisations:** Employers, directorships, founders, partners, authorised signatories, vendors, clients, and other typed affiliations.
6. **Property & projects:** Linked projects, properties, ownership/occupancy/tenancy/transaction roles, ownership percentage where documented, and effective dates.
7. **Documents & compliance:** Identity documents and KYC/compliance status. Mask sensitive identifiers by default; require dedicated permissions and access logging to reveal them.
8. **Activity & communication history:** Calls, messages, emails, meetings, notes, tasks, and follow-ups, each with actor, timestamp, source, and visibility.
9. **Relationships:** Family, professional, business, and legal relationships as explicit typed edges; never infer sensitive family or personal relationships.
10. **Audit & data quality:** Record provenance, duplicate candidates, merge history, corrections, ownership, and access events.

### Contact profile actions

- Create and edit only with the relevant permission.
- Add or remove a communication assignment without deleting the shared communication point.
- Mark a communication point primary only within a clearly defined entity and purpose context.
- Record verification and consent independently; a verified channel is not automatically consent for every communication purpose.
- Merge duplicate person records only through a reviewed, reversible, audited workflow.
- Mark deceased or inactive lifecycle states without hard-deleting the person or erasing historical relationships.

## 4. Company 360° profile

The Company 360° screen is the unified view of an organisation:

1. **Overview:** Legal name, trading names, organisation type, lifecycle status, registration jurisdiction, and key identifiers.
2. **Branches:** Branch locations, branch codes, operational contacts, and parent-child structure.
3. **Communication:** Shared phone/email assignments with purpose, branch scope, verification, consent, and effective dates.
4. **Registrations & compliance:** Applicable identifiers and registrations such as CIN/LLPIN, GSTIN, PAN, TAN, RERA registration, and licences. Keep jurisdiction and validity explicit; show only applicable fields.
5. **People & roles:** Directors, designated partners, founders, promoters, authorised signatories, employees, advisors, and other typed roles, with appointment/cessation dates and source evidence.
6. **Ownership & control:** Shareholding or beneficial ownership only where sourced and authorised. Preserve percentage, class, effective date, source, and confidence; do not infer control from role titles.
7. **Projects & properties:** Developer, builder, channel-partner, owner, landlord, tenant, service-provider, and transaction relationships.
8. **Documents:** Incorporation, registration, tax, RERA, contracts, authorisations, and compliance evidence with versioning and access control.
9. **Activity & finance links:** Communication timeline, leads, deals, invoices, payments, service cases, and tasks, subject to module permissions.
10. **Audit & data quality:** Source provenance, verification, duplicate resolution, ownership, and change history.

A branch must inherit organisation context for display but must retain its own branch-level address and communication assignments. Do not copy parent details into branch rows as independent facts.

## 5. Global Data Intelligence Engine

The engine is a cross-module data governance and intelligence layer. It should improve data quality without silently changing canonical records.

### 5.1 Capabilities

- **Normalisation:** Phone number E.164 normalisation, email canonicalisation, whitespace and Unicode normalisation, and controlled address/name formatting. Preserve original submitted values for traceability.
- **Name dictionary:** Search, suggest, transliterate, alias, and de-duplicate reusable names. Keep language/script and category metadata. Human approval is required for sensitive or ambiguous merges.
- **Identity resolution:** Generate duplicate candidates using explainable signals (exact verified channel, exact registration identifier, name/address similarity, and contextual relationships). A match score is a review aid, not proof of identity.
- **Communication assignment intelligence:** Detect shared channels, conflicting primary assignments, stale assignments, bounced email, invalid phone formats, and unverified channels.
- **Relationship intelligence:** Identify missing dates, impossible temporal overlaps, orphan relationships, and inconsistent role assignments.
- **Address intelligence:** Validate structured components and postal codes against approved reference sources; retain source and validation timestamp.
- **Data provenance:** Record source system, source record ID, capture actor, import/batch ID, verification method, confidence, and timestamps.
- **Quality scoring:** Provide field-level completeness, validity, freshness, and confidence indicators. Do not collapse these into a misleading single “verified” flag.
- **Governed recommendations:** Suggest corrections, merges, and missing links for human review. No silent auto-merge or destructive correction.

### 5.2 Processing pipeline

1. Capture submitted data and preserve the raw input.
2. Normalise into comparison forms without discarding the original.
3. Validate format and applicable reference data.
4. Resolve likely canonical entities and generate explainable candidate matches.
5. Apply tenant/ownership and access policy checks before exposing candidates.
6. Queue ambiguous or consequential changes for authorised review.
7. Apply approved changes as audited events.
8. Recompute affected quality indicators and search indexes.
9. Retain provenance and a reversible correction/merge history.

### 5.3 Confidence and review

- **Deterministic match:** Exact strong identifier with compatible entity type and no conflicting evidence. Still enforce permissions and duplicate-review policy.
- **High-confidence candidate:** Multiple independent matching signals; route for authorised review.
- **Possible match:** Similarity-based candidate only; never auto-merge.
- **Conflict:** Contradictory identifiers or incompatible facts; block automatic resolution and require investigation.

Thresholds must be calibrated using real, permissioned data and reviewed before activation. Do not invent universal numeric thresholds.

## 6. Global ownership, tenancy, and access

Ownership and access are separate concepts:

- **Ownership** answers who is accountable for the record and may govern its lifecycle.
- **Access** answers who may view, create, edit, export, merge, or disclose it.
- **Assignment** answers why an entity is connected to a communication point, organisation, branch, property, or project.
- **Consent** answers which communication purposes and channels are permitted.

Every read and write must respect tenant boundaries, role permissions, field-level restrictions, and audit requirements. Cross-tenant shared identities must not expose another tenant's notes, documents, activities, or private relationship context. Where a globally reusable communication point is referenced by multiple tenants, tenant-specific assignment metadata and access must remain isolated.

Platform-level dictionary administration must be separate from tenant-level suggestions and corrections. Tenant suggestions may be submitted for review; they must not silently rewrite global canonical entries.

## 7. Privacy, security, and lifecycle

- Apply least privilege and field-level access for identity documents, personal identifiers, ownership data, and sensitive relationship information.
- Mask sensitive identifiers in lists, search results, exports, and general profile views.
- Log reveal, export, merge, reassignment, and administrative correction events.
- Store consent purpose, channel, source, timestamp, and withdrawal history. Honour suppression and opt-out rules.
- Use soft deletion or archival where legally and operationally appropriate; preserve required audit and transaction history.
- Define retention and erasure rules by data category and applicable law before implementation.
- Do not infer or score protected or sensitive personal characteristics.

## 8. Search, UI/UX, and performance

- Provide one global search entry point with entity-type filters and permission-aware results.
- Clearly distinguish Person, Organisation, Branch, Communication Point, Address, Project, and Property results.
- Show why a duplicate candidate was suggested; do not expose hidden tenant data as evidence.
- Use a consistent profile header, section navigation, status indicators, activity timeline, and responsive layouts.
- Load large profile sections independently; paginate activity and relationship lists.
- Provide empty, loading, error, restricted, and stale-data states.
- Ensure keyboard access, visible focus, semantic labels, and mobile-friendly controls.
- Keep primary actions predictable and avoid destructive actions in overflow menus without confirmation.

## 9. API and event boundaries

Future APIs should be resource-oriented and permission-checked. Proposed conceptual resources:

- `/api/v1/people`
- `/api/v1/organisations`
- `/api/v1/organisations/{organisation}/branches`
- `/api/v1/communication-points`
- `/api/v1/communication-assignments`
- `/api/v1/relationships`
- `/api/v1/name-dictionary`
- `/api/v1/data-quality/candidates`
- `/api/v1/data-quality/reviews`

Proposed domain events include `PersonCreated`, `OrganisationUpdated`, `CommunicationPointAssigned`, `CommunicationPointVerified`, `RelationshipExpired`, `DuplicateCandidateRaised`, `IdentityMergeApproved`, and `DataQualityIssueResolved`.

These are design contracts, not implemented routes or events. Use idempotency keys for retriable writes, pagination for collections, explicit validation errors, and audit context for consequential operations.

## 10. Implementation sequence and acceptance gates

### Phase A — Specification closure
- Confirm entity definitions, relationship taxonomy, global ownership semantics, and communication assignment rules.
- Produce a reviewed logical ERD and field-level data dictionary.
- Define permissions, consent, retention, and cross-tenant visibility matrix.
- Review migration impact against the existing schema before any code or database change.

### Phase B — Foundation
- Implement canonical identity and assignment services behind tests.
- Add provenance, audit, and data-quality review primitives.
- Backfill only after approved mapping, dry-run reconciliation, backup, and rollback plan.

### Phase C — 360° profiles
- Deliver Contacts 360° and Company 360° read models and profile screens.
- Add authorised edits, assignment workflows, verification, and history.
- Test tenant isolation and field-level permissions.

### Phase D — Intelligence
- Add normalisation, duplicate candidate generation, review queues, quality scoring, and controlled dictionary workflows.
- Begin in recommendation-only mode; enable approved automation only after measured precision and operational review.

### Release acceptance criteria
- No duplicate canonical identity is created by merely adding another communication assignment.
- Shared mobile/email assignments do not leak private data across tenants.
- Global dictionary changes are versioned, attributable, and reviewable.
- Contact/company relationships preserve type, scope, source, and validity dates.
- Duplicate suggestions are explainable and never silently merge records.
- Sensitive identifiers are masked by default and reveal/export events are audited.
- All profile sections honour the same permission model as their underlying modules.
- Tests cover tenant isolation, overlapping assignments, historical relationships, merge rollback, consent withdrawal, and audit events.

## 11. Explicit non-goals for this specification

- No database migration or schema deployment.
- No bulk merge, automatic identity correction, or destructive deduplication.
- No unreviewed cross-tenant data sharing.
- No assumption that a shared phone/email proves identity, ownership, employment, or consent.
- No replacement of source-system records without provenance and reconciliation.
