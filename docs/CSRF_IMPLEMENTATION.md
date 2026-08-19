# CSRF Implementation

The application now uses a strict server-side CSRF gate for every POST request.

## Contract
- HTML forms must submit `_csrf`.
- AJAX clients must send `X-CSRF-Token`.
- Missing, malformed, or mismatched tokens return HTTP 419.
- There is no same-origin or Referer fallback.
- GET requests are not subject to the POST CSRF gate.

## UI integration
Use the centralized helper when rendering a form:

```php
<input type="hidden" name="_csrf" value="<?= htmlspecialchars(Security::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
```

For JavaScript requests:

```js
headers: { 'X-CSRF-Token': csrfToken }
```

## QA gate
The strict validator is implemented in `services/Security.php`. Existing forms must be migrated to submit the token before their state-changing POST actions are used in production.
