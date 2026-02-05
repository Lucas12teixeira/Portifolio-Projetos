# 🚀 Quick Start Guide

Get American Teens up and running in under 10 minutes!

## ⚡ Prerequisites Check

Before starting, ensure you have:

```bash
# Check PHP version (need 7.4+)
php --version

# Check MySQL (need 5.7+)
mysql --version

# Check Git
git --version
```

If any are missing, see [Installation Guide](INSTALLATION.md) for setup instructions.

---

## 🗄️ 2. Setup Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE americateens;
EXIT;

# Import schema
mysql -u root -p americateens < api/sql/INSTALAR-TUDO.sql
```

---

## ⚙️ 3. Configure

```bash
# Copy config example
cp api/config.example.php api/config.php

# Edit configuration (use your favorite editor)
nano api/config.php
```

**Minimum required changes:**

```php
define('DB_USER', 'your_mysql_user');
define('DB_PASS', 'your_mysql_password');
define('JWT_SECRET', 'change-this-to-random-string');
```

**Generate secure JWT secret:**

```bash
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

---

## 🚀 4. Start Server

**Option A: PHP Built-in Server (Easiest)**

```bash
php -S localhost:8000
```

**Option B: Apache/Nginx**

Configure virtual host (see [INSTALLATION.md](INSTALLATION.md#step-6-configure-web-server))

---

## 🌐 5. Open Browser

```
http://localhost:8000
```

---

## 👤 6. Create First User

1. Click "Create Account" or navigate to `http://localhost:8000/#auth`
2. Fill in registration form
3. Click "Register"

**🎉 Congratulations!** Your first user is automatically an Administrator!

---

## ✅ 7. Verify Installation

Test these features:
- [ ] Login successful
- [ ] Dashboard loads
- [ ] Profile page works
- [ ] Chat interface loads
- [ ] Events page accessible

---

## 🎯 Next Steps

- 📖 Read [Documentation](docs/)
- 🎨 Customize styling in `css/`
- 🔧 Explore API endpoints in `api/`
- 📱 Test PWA features (install to home screen)
- 👥 Invite team members

---

## 🐛 Troubleshooting

### Issue: Database connection fails

```bash
# Verify MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u root -p -e "SHOW DATABASES;"
```

### Issue: API returns 404

```bash
# Check .htaccess exists
ls -la api/.htaccess

# For Apache, enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Issue: Can't create first user

- Check database imported successfully
- Verify `members` table exists
- Check browser console for errors
- Review `api/logs/error.log`

---

## 📚 More Help

- **Full Installation**: [INSTALLATION.md](INSTALLATION.md)
- **Configuration**: [INSTALLATION.md#configuration](INSTALLATION.md#configuration)
- **Deployment**: [INSTALLATION.md#deployment](INSTALLATION.md#deployment)
- **Issues**: [GitHub Issues](https://github.com/yourusername/american-teens/issues)

---

## 🎉 You're Ready!

American Teens is now running locally. Start exploring and building your community!

**Questions?** Check the [documentation](docs/) or [open an issue](https://github.com/yourusername/american-teens/issues/new).

---

<div align="center">

**[← Back to README](README.md)**

Made with ❤️ by the American Teens team

</div>
