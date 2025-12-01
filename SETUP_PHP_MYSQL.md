# PHP & MySQL Setup Guide for macOS

## ✅ PHP Installation Status

**PHP Version**: 8.5.0 (Installed via Homebrew)  
**Location**: `/opt/homebrew/opt/php/`  
**Configuration**: `/opt/homebrew/etc/php/8.5/`

### Required Extensions (All Installed ✅)
- ✅ mysqli (MySQL extension)
- ✅ session (Session management)
- ✅ mbstring (Multibyte string support)
- ✅ fileinfo (File type detection)

---

## 🗄️ MySQL Installation

### Option 1: Install MySQL via Homebrew (Recommended)

```bash
# Install MySQL
brew install mysql

# Start MySQL service
brew services start mysql

# Secure MySQL installation (set root password)
mysql_secure_installation
```

### Option 2: Install MAMP (All-in-One Solution)

MAMP includes PHP, MySQL, and Apache in one package:
1. Download from: https://www.mamp.info/en/downloads/
2. Install and start MAMP
3. Access via: http://localhost:8888/

### Option 3: Install XAMPP

XAMPP includes PHP, MySQL, and Apache:
1. Download from: https://www.apachefriends.org/
2. Install and start XAMPP
3. Access via: http://localhost/

---

## 🌐 Web Server Options

### Option 1: PHP Built-in Server (Quick Testing)

For development, you can use PHP's built-in server:

```bash
cd /Users/akshatsingh/Downloads/iwpproject
php -S localhost:8000
```

Then access: http://localhost:8000/

**Note**: Built-in server doesn't support `.htaccess` files, but works for basic testing.

### Option 2: Apache (Full Web Server)

If you want to use Apache:

```bash
# Install Apache
brew install httpd

# Start Apache
brew services start httpd

# Configure PHP module in Apache
# Edit /opt/homebrew/etc/httpd/httpd.conf
# Add:
LoadModule php_module /opt/homebrew/opt/php/lib/httpd/modules/libphp.so

<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>

DirectoryIndex index.php index.html
```

### Option 3: Nginx (Alternative Web Server)

```bash
# Install Nginx
brew install nginx

# Start Nginx
brew services start nginx

# Configure PHP-FPM
brew services start php
```

---

## 🚀 Quick Start with PHP Built-in Server

Since PHP is already installed, you can start testing immediately:

```bash
# Navigate to project directory
cd /Users/akshatsingh/Downloads/iwpproject

# Start PHP built-in server
php -S localhost:8000
```

Then open your browser:
- Frontend: http://localhost:8000/
- Admin: http://localhost:8000/admin/

**Note**: You'll still need MySQL for the database functionality.

---

## 📋 Complete Setup Checklist

### 1. PHP ✅
- [x] PHP installed (8.5.0)
- [x] Required extensions installed
- [x] PHP CLI working

### 2. MySQL ⏳
- [ ] MySQL installed
- [ ] MySQL service running
- [ ] Database created (`avipro_travels`)
- [ ] Database schema imported (`database.sql`)

### 3. Web Server ⏳
- [ ] Web server configured (Apache/Nginx/MAMP/XAMPP)
- [ ] PHP module enabled
- [ ] Project accessible via browser

### 4. Project Setup ⏳
- [ ] Database credentials configured
- [ ] BASE_URL configured
- [ ] Upload directories created
- [ ] Permissions set

---

## 🔧 Next Steps

1. **Install MySQL** (choose one):
   ```bash
   brew install mysql
   brew services start mysql
   ```

2. **Create Database**:
   ```bash
   mysql -u root -p
   CREATE DATABASE avipro_travels;
   exit;
   ```

3. **Import Schema**:
   ```bash
   mysql -u root -p avipro_travels < database.sql
   ```

4. **Configure Project**:
   - Edit `config/database.php` with MySQL credentials
   - Edit `config/config.php` with correct BASE_URL

5. **Start Web Server**:
   - Use PHP built-in: `php -S localhost:8000`
   - Or use MAMP/XAMPP
   - Or configure Apache/Nginx

6. **Test Installation**:
   - Visit: http://localhost:8000/verify-installation.php
   - Check for any errors

---

## 📝 Configuration Files Location

- **PHP Config**: `/opt/homebrew/etc/php/8.5/php.ini`
- **PHP-FPM Config**: `/opt/homebrew/etc/php/8.5/php-fpm.ini`
- **Project Config**: `/Users/akshatsingh/Downloads/iwpproject/config/`

---

## 🆘 Troubleshooting

### PHP Not Found in Terminal
Add PHP to PATH:
```bash
echo 'export PATH="/opt/homebrew/opt/php/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

### MySQL Connection Issues
- Check MySQL service is running: `brew services list`
- Verify credentials in `config/database.php`
- Test connection: `mysql -u root -p`

### Web Server Issues
- Check if port is in use: `lsof -i :8000`
- Try different port: `php -S localhost:8080`
- Check PHP errors: `tail -f /opt/homebrew/var/log/php-fpm.log`

---

## ✅ Verification Commands

```bash
# Check PHP version
php -v

# Check PHP extensions
php -m | grep mysqli

# Check MySQL version
mysql --version

# Check MySQL service
brew services list | grep mysql

# Test PHP server
php -S localhost:8000
```

---

**Current Status**: PHP ✅ Installed | MySQL ⏳ Pending | Web Server ⏳ Pending

