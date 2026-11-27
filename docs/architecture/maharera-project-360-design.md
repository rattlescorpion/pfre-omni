# MahaRERA Project 360° — PFRECRM Design

**Status:** Preview implementation branch; migration and feature tests must be run before deployment.  
**Source:** MahaRERA Real Estate Agent Handbook 2025 supplied by PFRE (117 pages).

## Product objective

Make a project a structured development master record rather than a simple name/location row. Project 360° links phases, promoter, land, approvals, documents, professionals, progress disclosures, due diligence and unit inventory.

## Handbook-to-system mapping

| Handbook topic | PFRECRM module / data |
|---|---|
| Section 4(2)(a)–(b), project application (handbook pp. 56–57) | Developer/promoter link; enterprise identity; promoter's previous projects and prior-project status/delay/cases/land/payment notes |
| Section 4(2)(c)–(d) (pp. 57–58) | Approval and commencement-certificate register; sanctioned plan, layout plan and specifications; phase-specific records |
| Section 4(2)(e)–(f) (p. 57) | Development works and proposed facilities; project boundary and endpoint latitude/longitude |
| Section 4(2)(g)–(i) (p. 57) | Allotment letter, agreement for sale, conveyance deed templates; unit type and carpet area; exclusive balcony/verandah/open terrace areas; garage count and area |
| Section 4(2)(j)–(k) (p. 57) | Project real-estate agents, contractors, architect, structural engineer and other development professionals linked to Directory 360° |
| Section 4(2)(l) (pp. 57–58) | Title/encumbrance declarations, committed completion period, separate account declaration, certification and annual audit references |
| Sections 6.3–6.4 (pp. 59–60) | Extension, lapse, revocation and de-registration status tracking |
| Section 11 quarterly updates (pp. 61–63) | Booked apartments/plots/garages, approvals received/pending, progress and reported status |
| Chapter 8 (pp. 74–78) | Promoter identity, title, approvals/CC, taxes, litigation, allotment/agreement, MahaRERA carpet-area review |

The handbook says a phase is a stand-alone real estate project for registration (Section 3 discussion, p. 56). Therefore a phase must have its own registration number, dates, approval records and progress disclosures; do not assume one parent registration covers every phase.

## Project 360° sections

1. **Project identity:** name, type (residential/commercial/mixed/plotted/redevelopment), developer/promoter, address, locality, website and description.
2. **Land & location:** land area, survey/hissa/CTS references, land type, title-review state, encumbrance details, boundary description and boundary coordinates. These are stored as separate land-parcel records; a title-review label is not a legal title opinion.
3. **Phases:** phase name, MahaRERA registration number, registration state, registration and validity dates, proposed/actual completion, phase land area and status.
4. **Buildings / wings / units:** link the existing Properties inventory to the project and phase; preserve separate building, society, wing, floor, configuration and unit identifiers. Capture MahaRERA carpet area separately from exclusive balcony, verandah and open-terrace areas, plus garage area/count. The carpet-area definition excludes external walls, service shafts, exclusive balcony/verandah and exclusive open terrace, while including internal partition walls.
5. **Approvals:** approval type, authority, application/reference number, status, application/issue/expiry dates and evidence document. Include IOD, commencement certificate (including stage/floor limits), sanctioned plans, fire/environment/other NOCs and occupancy certificate.
6. **Documents:** title deeds, encumbrance certificates, approvals, plans, allotment letter, agreement for sale, conveyance deed, MahaRERA certificate, quarterly updates and Engineer / Architect / CA / annual audit certificates. Store privately by default; public visibility must be an explicit reviewed flag.
7. **Professionals & counterparties:** engineer, architect, structural engineer, chartered accountant in practice, contractors and project real-estate agents. Select existing individuals from Phonebook/Contacts 360° and firms from Companies 360° (currently both entity types are represented by `directory_entities`). Keep only a relationship record in the project; do not copy contact/company data. Company 360° also stores the promoter's previous project track record, including reported status, delay details, pending cases, land type and pending payments.
8. **Financial compliance:** separate-account disclosure/status, separate-account share, withdrawal percentage, completion percentage and links to Engineer, Architect and Chartered Accountant certificates plus annual audit evidence. Bank account numbers are deliberately excluded from this screen.
9. **Quarterly progress:** period, publication date, completion percentage, apartments/plots/garages booked, approvals received/pending, reported status, summary and source link.
10. **Due diligence:** promoter identity, project registration (including any exemption claim for review), land title, encumbrances, approvals, commencement certificate, tax/utility payments, litigation, allotment/agreement and carpet area.
11. **Audit trail:** creator, updater, verifier, dates, status history and evidence source.

## Current implementation in this branch

- Additive migrations extend `projects` and `properties`, and create `project_phases`, `project_land_parcels`, `project_professionals`, `project_documents`, `project_approvals`, `project_progress_updates`, `project_due_diligence_checks`, `project_financial_compliance_records` and Company 360° promoter track-record storage.
- Project create/edit screens capture project identity, development overview, FSI, facilities and boundary description.
- Project detail screen displays project overview, land parcels, phases, professionals, approvals, documents, due-diligence status, quarterly updates and linked properties including separate carpet/balcony/verandah/terrace/garage area fields.
- Forms are provided for phases, professionals, approvals, document upload, due-diligence checks and quarterly updates.
- Professional links use `directory_entities`, including both person and company records, with per-user ownership checks.
- Uploaded project documents use private local storage and a protected download route. Documents default to unverified and non-public.

## Public website vs CRM data boundary

- PFRECRM is the operational source of truth.
- The public website must receive only explicitly approved public fields.
- Never publish title deeds, PAN/Aadhaar, bank details, private agreements, private litigation evidence or internal notes.
- Do not label a project “verified”, “audited”, “registered” or “compliant” unless that exact status and its source/date are recorded and reviewed.
- The website should state that customers must check the current MahaRERA portal and consult qualified professionals. A stored number alone is not a due-diligence certificate.
- Public documents require explicit publication approval and an approved public URL; private CRM file paths must never be emitted to the website.

## Important legal/product distinction

This handbook is a training reference, not a substitute for the Act, current Maharashtra Rules/Regulations, MahaRERA orders or professional legal advice. The handbook itself says users should cross-check facts and law against original Government notifications (publication note, p. 4). Treat thresholds, timelines and status labels as configurable and verify current law before using them as automated legal conclusions.

## Preview acceptance checklist

- [ ] Existing project/property data survives the additive migration.
- [ ] Parent project can have multiple phases, each with its own registration reference.
- [ ] A professional can be linked from a person or company directory record and reused across projects.
- [ ] Removing a project link does not delete the source person/company.
- [ ] Duplicate professional links are rejected.
- [ ] Private document uploads cannot be fetched without authorization.
- [ ] Documents remain unverified/non-public until an authorized reviewer changes their state.
- [ ] Due-diligence checks distinguish pending, verified, issue found and not applicable.
- [ ] Quarterly updates retain period and source.
- [ ] Public website shows only approved data and does not assert verification by default.
- [ ] Responsive layouts, validation, permissions, route names and migration rollback are tested.
- [ ] Confirm the intended CRM database and backup before running migrations; do not run on Production from this branch.
