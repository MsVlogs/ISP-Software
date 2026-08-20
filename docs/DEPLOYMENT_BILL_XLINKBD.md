# bill.xlinkbd.net Deployment Verification

## Server
- Host: `bill`
- Application path: `/var/www/isp-billing-import`
- Public document root: `/var/www/isp-billing-import/public`
- Domain: `http://bill.xlinkbd.net`

## Verified runtime state
- Nginx service: active
- Apache: not installed/available
- PHP-FPM: `php8.3-fpm.service` active
- PHP-FPM sockets: default `php8.3-fpm.sock` and service2 socket present
- Firewall (UFW): inactive
- DNS: `bill.xlinkbd.net` resolves to `103.103.33.149`
- Laravel `public/index.php`: present
- Laravel route list: 168 routes loaded successfully
- Laravel asset build: passed

## Deployment blocker
Nginx is currently listening on `0.0.0.0:8080`, not port 80. Its active configuration is for `service2.yourdomain.com` with root `/var/www/isp-software` and the service2 PHP-FPM socket. No active Nginx server block for `bill.xlinkbd.net` was found.

Therefore `http://bill.xlinkbd.net` currently returns connection refused on port 80.

## Required server configuration
Create/enable an Nginx server block for `bill.xlinkbd.net`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name bill.xlinkbd.net;

    root /var/www/isp-billing-import/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \\.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\\.ht {
        deny all;
    }
}
```

Then run `nginx -t` and reload Nginx, followed by local and domain HTTP smoke tests.

## Note
This deployment configuration is intentionally tracked separately from `.env` secrets. No credentials are stored in this document.
