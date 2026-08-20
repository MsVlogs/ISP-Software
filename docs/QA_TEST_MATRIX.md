# QA Test Matrix

| Area | Static QA | Runtime QA | Gate |
|---|---|---|---|
| Authentication | Reviewed | Pending | BLOCKED |
| Routing | Reviewed | Pending | BLOCKED |
| Database | Reviewed | Pending | BLOCKED |
| Billing | Reviewed | Pending | BLOCKED |
| Customers | Reviewed | Pending | BLOCKED |
| MikroTik | Reviewed | Pending device access | BLOCKED |
| OLT/ONU | Reviewed | Pending device access | BLOCKED |
| Network monitoring | Reviewed | Pending device access | BLOCKED |
| Income/Expense | Reviewed | Pending | BLOCKED |
| Inventory | Reviewed | Pending | BLOCKED |
| Employee/HR | Reviewed | Pending | BLOCKED |
| Complaints | Reviewed | Pending | BLOCKED |
| Reports | Reviewed | Pending | BLOCKED |
| Bilingual UI | Reviewed | Pending browser QA | BLOCKED |
| Destructive actions | Reviewed | Pending auth tests | BLOCKED |

## Required final runtime suite
1. Login/logout/session expiry.
2. CRUD create/edit/activate/deactivate/delete with authorization checks.
3. Billing collection, due, ledger and report consistency.
4. Database outage and reconnect behavior.
5. Invalid/missing request parameters.
6. SQL injection payloads against all dynamic query surfaces.
7. CSRF protection for state-changing requests.
8. XSS payloads in customer, complaint, inventory and employee fields.
9. MikroTik/OLT timeout, authentication failure and unavailable-device behavior.
10. Mobile/desktop UI smoke test in English and বাংলা.
