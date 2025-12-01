# Avipro Travels - Installation Guide

## Quick Start Guide

Follow these steps to set up the Avipro Travels website on your local machine.

### Step 1: Prerequisites

Ensure you have the following installed:
- **PHP 7.4 or higher** (Check: `php -v`)
- **MySQL 5.7 or higher** (Check: `mysql --version`)
- **Apache/Nginx web server**
- **phpMyAdmin** (recommended for database management)

### Step 2: Extract Project Files

1. Extract the project ZIP file to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\iwpproject\`
   - **WAMP**: `C:\wamp64\www\iwpproject\`
   - **MAMP**: `/Applications/MAMP/htdocs/iwpproject/`
   - **Linux**: `/var/www/html/iwpproject/`

### Step 3: Database Setup

#### Option A: Using phpMyAdmin (Recommended)

1. Open phpMyAdmin in your browser (usually `http://localhost/phpmyadmin`)
2. Click on "New" to create a new database
3. Name it `avipro_travels`
4. Select the database
5. Click on "Import" tab
6. Choose `database.sql` file
7. Click "Go" to import

#### Option B: Using Command Line

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE avipro_travels;"

# Import schema
mysql -u root -p avipro_travels < database.sql

# (Optional) Import sample data
mysql -u root -p avipro_travels < sample-data.sql
```

### Step 4: Configure Database Connection

1. Open `config/database.php`
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');        // Your MySQL username
   define('DB_PASS', '');            // Your MySQL password
   define('DB_NAME', 'avipro_travels');
   ```

### Step 5: Configure Base URL

1. Open `config/config.php`
2. Update BASE_URL to match your setup:
   ```php
   define('BASE_URL', 'http://localhost/iwpproject/');
   ```
   
   **Note**: If your project is in a subdirectory, include it in the URL:
   - Example: `http://localhost/myprojects/iwpproject/`

### Step 6: Set File Permissions

**On Linux/Mac:**
```bash
chmod 755 uploads/
chmod 755 uploads/packages/
chmod 755 uploads/banners/
```

**On Windows:**
- Right-click on `uploads` folder
- Properties → Security → Edit
- Give "Modify" permission to your web server user

### Step 7: Test Installation

1. **Start your web server** (Apache/Nginx)
2. **Start MySQL service**
3. **Open browser** and navigate to:
   - Frontend: `http://localhost/iwpproject/`
   - Admin Panel: `http://localhost/iwpproject/admin/`

### Step 8: Login to Admin Panel

- **URL**: `http://localhost/iwpproject/admin/`
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **Important**: Change the default password after first login!

## Troubleshooting

### Database Connection Error

**Error**: "Connection failed: Access denied"

**Solution**:
1. Check MySQL service is running
2. Verify database credentials in `config/database.php`
3. Ensure database `avipro_travels` exists
4. Check MySQL user permissions

### Image Upload Not Working

**Error**: Images not uploading or displaying

**Solution**:
1. Check `uploads/` directory exists and is writable
2. Verify PHP `upload_max_filesize` in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Restart Apache after changing PHP settings

### Page Not Found (404 Error)

**Error**: Pages showing 404 or blank screen

**Solution**:
1. Check BASE_URL in `config/config.php` matches your setup
2. Ensure `.htaccess` file exists (for Apache)
3. Verify mod_rewrite is enabled in Apache
4. Check file paths are correct

### Session Not Working

**Error**: Admin login not persisting

**Solution**:
1. Check PHP session directory is writable
2. Verify `session_start()` is called in `config/config.php`
3. Check PHP `session.save_path` in `php.ini`

### AJAX Requests Failing

**Error**: Booking form not submitting

**Solution**:
1. Open browser Developer Tools (F12)
2. Check Console for JavaScript errors
3. Check Network tab for failed requests
4. Verify API endpoint URLs are correct
5. Check PHP error logs

## PHP Configuration Requirements

Ensure these PHP settings in `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
max_input_time = 300
session.gc_maxlifetime = 1800
```

## Apache Configuration

If using Apache, ensure these modules are enabled:
- `mod_rewrite`
- `mod_headers` (optional, for security headers)

Enable in Apache:
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo service apache2 restart
```

## Testing Checklist

After installation, verify:

- [ ] Frontend homepage loads correctly
- [ ] Admin login works
- [ ] Can add/edit/delete packages
- [ ] Can upload package images
- [ ] Booking form submits successfully
- [ ] Bookings appear in admin panel
- [ ] Site content can be updated
- [ ] Contact information can be updated

## Sample Data

To populate the database with sample packages:

```bash
mysql -u root -p avipro_travels < sample-data.sql
```

Or import `sample-data.sql` through phpMyAdmin.

## Support

For issues or questions:
1. Check the README.md file
2. Review PHP error logs
3. Check browser console for JavaScript errors
4. Verify all prerequisites are installed correctly

---

**Installation Date**: _______________
**Installed By**: _______________
**Notes**: _______________

