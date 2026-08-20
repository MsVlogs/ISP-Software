# bill.xlinkbd.net Deployment Verification

## Target architecture
- One public domain: `bill.xlinkbd.net`
- Main site: `http://bill.xlinkbd.net:80`
- Billing login/panel: `http://bill.xlinkbd.net:8081/login`
- Customer portal: `http://bill.xlinkbd.net:8082/login`
- Application: `/var/www/isp-billing-import`
- Laravel document root: `/var/www/isp-billing-import/public`
- PHP-FPM: PHP 8.4 (`/run/php/php8.4-fpm.sock`)

## Verified runtime state
- Nginx: active and serving ports 80, 8081 and 8082
- Apache: not used
- PHP 8.4-FPM: active/running
- PHP 8.3-FPM: intentionally stopped
- DNS: `bill.xlinkbd.net` resolves to `103.103.33.149`
- Laravel route table: loaded successfully
- Laravel assets: `npm run build` passed
- Storage/cache permissions: fixed for `www-data`

## Port-based Nginx routing
The application already contains billing and portal route groups based on the virtual host. To keep one public domain while preserving those existing route groups, Nginx sets the upstream `HTTP_HOST` per port:

```nginx
# :8081 billing
fastcgi_param HTTP_HOST billing.bill.xlinkbd.net;
fastcgi_pass unix:/run/php/php8.4-fpm.sock;

# :8082 portal
fastcgi_param HTTP_HOST portal.bill.xlinkbd.net;
fastcgi_pass unix:/run/php/php8.4-fpm.sock;
```

No `billing.bill.xlinkbd.net` or `portal.bill.xlinkbd.net` DNS records are required for the public URLs.

## Verification
- `http://bill.xlinkbd.net/` → HTTP 200/redirect response from Laravel
- `http://bill.xlinkbd.net:8081/login` → HTTP 200, Billing/Fortify login HTML
- `http://bill.xlinkbd.net:8082/login` → HTTP 200, Portal/Filament login HTML

## Note
Production secrets are not stored in this document.
