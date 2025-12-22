# HTTPS Setup Guide

This guide explains how to enable HTTPS for your Laravel application.

## Configuration Steps

### 1. Update Environment Variables

Add or update these variables in your `.env` file:

```env
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
```

### 2. Enable HTTPS Redirect in .htaccess (Apache)

If you're using Apache, uncomment the HTTPS redirect rules in `public/.htaccess`:

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 3. SSL Certificate Setup

You need to obtain an SSL certificate. Options include:

#### Option A: Let's Encrypt (Free)
```bash
# Install certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache

# Get certificate for Apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal (already set up by certbot)
```

#### Option B: Cloudflare (Free SSL)
1. Sign up for Cloudflare
2. Add your domain
3. Update nameservers
4. Enable SSL/TLS encryption mode: "Full" or "Full (strict)"

#### Option C: Commercial SSL Certificate
Purchase from providers like:
- DigiCert
- GlobalSign
- Comodo
- GoDaddy

### 4. Server Configuration

#### For Apache:
Ensure mod_ssl is enabled:
```bash
sudo a2enmod ssl
sudo systemctl restart apache2
```

#### For Nginx:
Add SSL configuration to your server block:
```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### 5. Laravel Configuration

The application is already configured to:
- Force HTTPS redirects in production (via `ForceHttps` middleware)
- Use secure session cookies when `SESSION_SECURE_COOKIE=true`
- Generate HTTPS URLs when `APP_URL` starts with `https://`

### 6. Testing

After setup, verify:
1. Visit `http://yourdomain.com` - should redirect to `https://`
2. Check browser shows padlock icon
3. Verify no mixed content warnings
4. Test all forms and authentication

### 7. Security Headers (Optional but Recommended)

Consider adding security headers in your web server configuration or Laravel middleware:

```php
// In bootstrap/app.php or middleware
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
```

## Troubleshooting

### Mixed Content Warnings
- Ensure all assets (CSS, JS, images) use HTTPS URLs
- Check `APP_URL` in `.env` is set to `https://`
- Use `asset()` helper which respects `APP_URL`

### Redirect Loops
- Check if you have multiple HTTPS redirects (Laravel + server)
- Disable one if both are active
- Verify SSL certificate is valid

### Session Issues
- Clear browser cookies
- Verify `SESSION_SECURE_COOKIE=true` in `.env`
- Check session domain configuration

## Notes

- The `ForceHttps` middleware only activates in production
- For local development, HTTPS is not required
- Always test in staging before production deployment

