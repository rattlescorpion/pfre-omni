# Property ownership service authorization

The ownership service enforces access to both sides of a property-to-directory link.

- Property access is currently limited to its creator or assigned user, using the legacy inventory columns `created_by` and `assigned_to`.
- Directory access is limited to the directory entity owner.
- Unauthorized records return 404 to avoid confirming their existence.
- Percentage values must be greater than zero and no more than 100.
- Date ranges cannot end before they start.
- Ownership links are archived with soft deletes.

This is a conservative compatibility boundary, not a replacement for a future tenant-aware Laravel Policy. A formal organization/tenant membership model must supersede creator/assignee checks before multi-tenant SaaS rollout.
