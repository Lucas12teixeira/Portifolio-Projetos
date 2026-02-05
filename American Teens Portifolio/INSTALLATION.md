# 📦 Installation Guide - American Teens

Complete guide for installing and configuring the American Teens platform.

## 📋 Table of Contents

- [System Requirements](#-system-requirements)
- [Quick Start](#-quick-start)
- [Detailed Installation](#-detailed-installation)
- [Configuration](#-configuration)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Upgrade Guide](#-upgrade-guide)

---

## 💻 System Requirements

### Minimum Requirements

| Component | Minimum Version | Recommended |
|-----------|----------------|-------------|
| PHP | 7.4 | 8.0+ |
| MySQL | 5.7 | 8.0+ |
| Apache/Nginx | 2.4 / 1.18 | Latest stable |
| Disk Space | 500 MB | 2 GB |
| RAM | 512 MB | 2 GB |

### PHP Extensions Required

```bash
- mysqli
- pdo_mysql
- json
- mbstring
- openssl
- curl
- gd (for image processing)
```

### Browser Requirements

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (latest versions)

---

## ⚡ Quick Start

**For experienced developers who want to get up and running quickly:**

```bash
# 1. Clone and navigate
git clone https://github.com/yourusername/american-teens.git
cd american-teens

# 2. Create database
mysql -u root -p -e "CREATE DATABASE americateens;"
mysql -u root -p americateens < api/sql/INSTALAR-TUDO.sql

# 3. Configure
cp api/config.example.php api/config.php
# Edit api/config.php with your database credentials

# 4. Start server
php -S localhost:8000

# 5. Open browser
# Navigate to http://localhost:8000
```

---

## 🔧 Detailed Installation

### Step 1: Download the Project

**Option A: Clone with Git (Recommended)**

```bash
git clone https://github.com/yourusername/american-teens.git
cd american-teens
```

**Option B: Download ZIP**

1. Download ZIP from [GitHub Releases](https://github.com/yourusername/american-teens/releases)
2. Extract to your desired location
3. Open terminal in extracted folder

### Step 2: Check System Requirements

**Check PHP version:**

```bash
php --version
# Should show PHP 7.4.0 or higher
```

**Check required PHP extensions:**

```bash
php -m | grep -E "mysqli|pdo_mysql|json|mbstring|openssl|curl|gd"
```

If any extensions are missing:

**Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install php-mysqli php-json php-mbstring php-curl php-gd
```

**CentOS/RHEL:**
```bash
sudo yum install php-mysqli php-json php-mbstring php-curl php-gd
```

**macOS (Homebrew):**
```bash
brew install php
```

**Windows:**
- Edit `php.ini`
- Uncomment extension lines (remove `;`)

### Step 3: Set Up Database

**3.1 Create MySQL Database:**

```bash
# Access MySQL
mysql -u root -p

# Create database
CREATE DATABASE americateens CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user (optional but recommended)
CREATE USER 'americateens_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON americateens.* TO 'americateens_user'@'localhost';
FLUSH PRIVILEGES;

# Exit MySQL
EXIT;
```

**3.2 Import Database Schema:**

```bash
# Import all tables and data
mysql -u root -p americateens < api/sql/INSTALAR-TUDO.sql

# Verify installation
mysql -u root -p americateens -e "SHOW TABLES;"
```

**Expected tables:**
```
+---------------------------+
| Tables_in_americateens    |
+---------------------------+
| chat_conversations        |
| chat_messages             |
| events                    |
| members                   |
| mural_comments            |
| mural_posts               |
| quiz_questions            |
| revelacao                 |
| verses_of_the_day         |
+---------------------------+
```

### Step 4: Configure Application

**4.1 Create Configuration File:**

```bash
# Copy example config
cp api/config.example.php api/config.php
```

**4.2 Edit Configuration:**

Open `api/config.php` in your editor and update:

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');        // Database host
define('DB_USER', 'americateens_user'); // Database user
define('DB_PASS', 'your_secure_password'); // Database password
define('DB_NAME', 'americateens');     // Database name
define('DB_CHARSET', 'utf8mb4');       // Character set

// JWT Configuration
define('JWT_SECRET', 'your-secret-key-change-this-in-production'); // CHANGE THIS!
define('JWT_EXPIRATION', 3600);        // 1 hour (in seconds)
define('REFRESH_TOKEN_EXPIRATION', 604800); // 7 days

// Application Configuration
define('APP_URL', 'http://localhost:8000'); // Your app URL
define('APP_ENV', 'development');      // development or production

// File Upload Configuration
define('UPLOAD_MAX_SIZE', 5242880);    // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Email Configuration (Optional)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', 'noreply@americateens.com');

// Security Settings
define('ENABLE_CORS', true);
define('ALLOWED_ORIGINS', ['http://localhost:8000']);
define('SESSION_LIFETIME', 86400);     // 24 hours

// Debug Mode (DISABLE IN PRODUCTION!)
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');
?>
```

**4.3 Generate Secure JWT Secret:**

```bash
# Generate random 256-bit key
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

Copy the output and use it as your `JWT_SECRET`.

**4.4 Configure Client-Side:**

Edit `config.js` in the root directory:

```javascript
const CONFIG = {
    API_URL: 'http://localhost:8000/api',
    APP_NAME: 'American Teens',
    VERSION: '1.0.0',
    
    // Feature flags
    ENABLE_CHAT: true,
    ENABLE_EVENTS: true,
    ENABLE_QUIZ: true,
    ENABLE_BIBLE: true,
    
    // Polling intervals (milliseconds)
    CHAT_POLL_INTERVAL: 3000,
    NOTIFICATION_POLL_INTERVAL: 10000,
    
    // Cache settings
    CACHE_VERSION: 'v1.0.0',
    CACHE_DURATION: 3600000, // 1 hour
};
```

### Step 5: Set Up File Permissions

**Linux/macOS:**

```bash
# Create necessary directories
mkdir -p api/logs uploads/avatars uploads/events uploads/posts

# Set permissions
chmod 755 api/logs uploads
chmod 644 api/config.php

# For Apache (www-data user)
sudo chown -R www-data:www-data uploads/ api/logs/

# For development server (your user)
chown -R $USER:$USER uploads/ api/logs/
```

**Windows:**

```powershell
# Create directories
New-Item -ItemType Directory -Force -Path api\logs, uploads\avatars, uploads\events, uploads\posts

# Grant permissions (IIS)
icacls "uploads" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "api\logs" /grant "IIS_IUSRS:(OI)(CI)F" /T
```

### Step 6: Configure Web Server

#### Option A: PHP Built-in Server (Development)

```bash
# Start server
php -S localhost:8000

# Access application
# Open http://localhost:8000 in browser
```

**Advantages:**
- ✅ Quick and easy
- ✅ No configuration needed
- ✅ Perfect for development

**Limitations:**
- ❌ Single-threaded (slow)
- ❌ Not for production use
- ❌ No HTTPS support

#### Option B: Apache Server (Recommended for Production)

**Install Apache:**

```bash
# Ubuntu/Debian
sudo apt-get install apache2

# CentOS/RHEL
sudo yum install httpd

# macOS (comes pre-installed)
# No installation needed
```

**Configure Virtual Host:**

Create `/etc/apache2/sites-available/americateens.conf`:

```apache
<VirtualHost *:80>
    ServerName americateens.local
    ServerAlias www.americateens.local
    DocumentRoot /var/www/americateens

    <Directory /var/www/americateens>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Enable rewrite module
    RewriteEngine On
    
    # API routing
    RewriteCond %{REQUEST_URI} ^/api/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^api/(.*)$ api/index.php?path=$1 [QSA,L]

    # Error and access logs
    ErrorLog ${APACHE_LOG_DIR}/americateens_error.log
    CustomLog ${APACHE_LOG_DIR}/americateens_access.log combined
</VirtualHost>
```

**Enable site:**

```bash
# Enable rewrite module
sudo a2enmod rewrite

# Enable site
sudo a2ensite americateens.conf

# Restart Apache
sudo systemctl restart apache2

# Add to /etc/hosts
echo "127.0.0.1 americateens.local" | sudo tee -a /etc/hosts
```

**Access:** http://americateens.local

#### Option C: Nginx Server

**Install Nginx:**

```bash
# Ubuntu/Debian
sudo apt-get install nginx php-fpm

# CentOS/RHEL
sudo yum install nginx php-fpm
```

**Configure Nginx:**

Create `/etc/nginx/sites-available/americateens`:

```nginx
server {
    listen 80;
    server_name americateens.local;
    root /var/www/americateens;
    index index.html index.php;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Main location
    location / {
        try_files $uri $uri/ /index.html;
    }

    # API routing
    location /api/ {
        try_files $uri $uri/ /api/index.php?$args;
        
        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param PATH_INFO $fastcgi_path_info;
        }
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /api/config\.php$ {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Enable site:**

```bash
# Create symlink
sudo ln -s /etc/nginx/sites-available/americateens /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
sudo systemctl restart php7.4-fpm

# Add to /etc/hosts
echo "127.0.0.1 americateens.local" | sudo tee -a /etc/hosts
```

### Step 7: Initial Setup

**7.1 Create First User:**

1. Open the application in your browser
2. Navigate to `#auth` (registration page)
3. Fill in the registration form:
   - Name: Your name
   - Email: your@email.com
   - Password: Secure password (8+ characters)
   - Church: Your church name
   - Birthday: Your date of birth

4. Click "Register"

**The first registered user automatically becomes an Administrator!**

**7.2 Verify Installation:**

Check that everything works:

- ✅ Login successful
- ✅ Dashboard loads
- ✅ Profile page accessible
- ✅ Chat interface loads
- ✅ Events page accessible
- ✅ Bible search works

---

## 🔒 Configuration

### Security Configuration

**1. Change Default Secrets:**

```php
// NEVER use default secrets in production!
define('JWT_SECRET', 'CHANGE-THIS-TO-RANDOM-STRING');
```

**2. Configure HTTPS (Production Only):**

```apache
# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName americateens.com
    Redirect permanent / https://americateens.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName americateens.com
    DocumentRoot /var/www/americateens

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
    
    # Include previous configuration...
</VirtualHost>
```

**3. Configure CORS:**

```php
// api/config.php
define('ALLOWED_ORIGINS', [
    'https://americateens.com',
    'https://www.americateens.com'
]);
```

### Email Configuration

**Using Gmail:**

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password'); // Not your regular password!
```

**Get Gmail App Password:**
1. Go to Google Account settings
2. Security > 2-Step Verification
3. App passwords
4. Generate new app password
5. Use this password in config

### File Upload Configuration

```php
// Adjust upload limits in php.ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300

// Or in .htaccess
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

---

## 🚀 Deployment

### Production Checklist

Before deploying to production:

- [ ] Change `JWT_SECRET` to secure random value
- [ ] Set `APP_ENV` to `'production'`
- [ ] Disable `DEBUG_MODE`
- [ ] Configure HTTPS/SSL
- [ ] Set up regular database backups
- [ ] Configure error logging
- [ ] Enable gzip compression
- [ ] Set up monitoring
- [ ] Configure firewall rules
- [ ] Implement rate limiting
- [ ] Review security headers
- [ ] Test all functionality

### Deployment Steps

**1. Upload Files:**

```bash
# Using rsync
rsync -avz --exclude 'api/config.php' ./ user@server:/var/www/americateens/

# Using FTP/SFTP
# Upload all files except api/config.php
```

**2. Configure Database:**

```bash
# On production server
mysql -u root -p -e "CREATE DATABASE americateens;"
mysql -u root -p americateens < api/sql/INSTALAR-TUDO.sql
```

**3. Configure Application:**

```bash
# Create production config
nano /var/www/americateens/api/config.php
# Use production values!
```

**4. Set Permissions:**

```bash
chmod 755 /var/www/americateens
chmod 644 /var/www/americateens/api/config.php
chown -R www-data:www-data /var/www/americateens
```

**5. Configure SSL:**

```bash
# Using Let's Encrypt (Certbot)
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d americateens.com -d www.americateens.com
```

**6. Test Deployment:**

- ✅ Access via HTTPS
- ✅ Test all features
- ✅ Check error logs
- ✅ Verify database connections
- ✅ Test file uploads

---

## 🔧 Troubleshooting

### Common Issues

#### Issue: "Database connection failed"

**Solution:**

```bash
# Check database is running
sudo systemctl status mysql

# Verify credentials in config.php
# Check database exists
mysql -u root -p -e "SHOW DATABASES;"

# Test connection
mysql -u americateens_user -p americateens
```

#### Issue: "API endpoints return 404"

**Solution:**

```bash
# Apache: Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess exists in api/ folder
ls -la api/.htaccess

# Verify AllowOverride is set to All in Apache config
```

#### Issue: "File upload fails"

**Solution:**

```bash
# Check directory permissions
ls -la uploads/

# Create directories if missing
mkdir -p uploads/{avatars,events,posts}
chmod 755 uploads/
chown -R www-data:www-data uploads/

# Check PHP upload settings
php -i | grep upload_max_filesize
```

#### Issue: "JWT token invalid"

**Solution:**

```php
// Verify JWT_SECRET is correctly set in config.php
// Clear browser cache and localStorage
// Re-login to get new token
```

#### Issue: "Service Worker not registering"

**Solution:**

```bash
# Must use HTTPS or localhost
# Check browser console for errors
# Verify sw.js is accessible
# Clear browser cache
```

### Debug Mode

Enable debug mode for detailed errors:

```php
// api/config.php
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);

// View error log
tail -f api/logs/error.log
```

---

## 🔄 Upgrade Guide

### Upgrading to New Version

**1. Backup Everything:**

```bash
# Backup database
mysqldump -u root -p americateens > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf americateens_backup_$(date +%Y%m%d).tar.gz /var/www/americateens/
```

**2. Download New Version:**

```bash
git fetch origin
git checkout v1.1.0  # Replace with actual version
```

**3. Run Migrations:**

```bash
# Check for migration scripts in api/sql/
ls api/sql/migrations/

# Run migrations
mysql -u root -p americateens < api/sql/migrations/v1.0-to-v1.1.sql
```

**4. Update Configuration:**

```bash
# Check for new config options
diff api/config.example.php api/config.php

# Add new settings to your config.php
```

**5. Clear Cache:**

```bash
# Clear application cache
rm -rf cache/*

# Clear browser cache
# Ctrl+Shift+Delete in browser
```

**6. Test:**

- ✅ Verify all features work
- ✅ Check error logs
- ✅ Test new features

---

## 📊 Performance Optimization

### Database Optimization

```sql
-- Add indexes for better performance
CREATE INDEX idx_chat_messages_conversation ON chat_messages(conversation_id);
CREATE INDEX idx_chat_messages_created ON chat_messages(created_at);
CREATE INDEX idx_events_date ON events(event_date);
CREATE INDEX idx_members_email ON members(email);

-- Optimize tables
OPTIMIZE TABLE chat_messages;
OPTIMIZE TABLE events;
OPTIMIZE TABLE members;
```

### Enable Caching

**Apache:**

```apache
# Enable expires module
sudo a2enmod expires

# Add to .htaccess
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### Enable Gzip

```apache
# Add to .htaccess
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

---

## 📞 Support

Need help with installation?

- 📚 Check [Documentation](docs/)
- 💬 [GitHub Discussions](https://github.com/yourusername/american-teens/discussions)
- 🐛 [Report Issue](https://github.com/yourusername/american-teens/issues)
- 📧 Email: your.email@example.com

---

## ✅ Post-Installation

Congratulations! 🎉 American Teens is now installed!

**Next Steps:**

1. **Customize**: Update branding, colors, and content
2. **Configure**: Set up email, events, and other features
3. **Invite**: Add your community members
4. **Launch**: Start using the platform!

**Enjoy building your digital community! 🚀**

---

<div align="center">

**[⬆ Back to Top](#-installation-guide---american-teens)**

Need help? [Open an issue](https://github.com/yourusername/american-teens/issues)

</div>
