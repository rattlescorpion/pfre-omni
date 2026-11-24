# Contacts & Companies 360° — identity document register

The identity-document register stores multiple identity/KYC documents per directory entity, including PAN, Aadhaar, passport, driving licence, voter ID/EPIC, ration card and other jurisdiction-specific documents.

## Security contract

- The document number is stored using Laravel's encrypted cast. The application encryption key must be backed up securely; encrypted values cannot be recovered if the key is lost.
- A keyed fingerprint is intended for duplicate detection without exposing the clear document number. Generate it with an HMAC using a dedicated secret, not a plain SHA hash. Do not use the fingerprint as an authentication credential.
- Do not include document numbers, fingerprints, storage keys or notes in logs, search indexes, API serialization or analytics.
- Document files must be stored in a private disk with authorization checks and short-lived signed downloads. Public storage URLs are prohibited.
- Only explicitly authorized KYC roles should view or verify identity documents. Verification events must be audited.
- Retention, consent, purpose limitation and deletion rules must be configured for the applicable jurisdiction before production use.

## Current scope

This slice creates the data model and encrypted attribute contract. It does not yet expose upload, preview, download, verification or deletion screens. Those require a private storage disk, malware scanning, role permissions and an audit log before sensitive documents are accepted.
