# Admin Panel Access Guide

## 🔐 How to Access Admin Panel

### Step 1: Go to Admin Login Page
**URL**: http://localhost:8000/admin/login.php

Or simply: http://localhost:8000/admin/
(It will automatically redirect to login page)

### Step 2: Login Credentials
- **Username**: `admin`
- **Password**: `admin123`

### Step 3: After Login
You will be redirected to the Admin Dashboard automatically.

---

## 🚨 Troubleshooting

### If Admin Panel Shows Blank Page:
1. Check PHP server is running: `ps aux | grep "php -S"`
2. Check MySQL is running: `mysql -u root -e "SHOW DATABASES;"`
3. Clear browser cache and cookies
4. Try accessing: http://localhost:8000/admin/login.php directly

### If Login Doesn't Work:
1. Verify admin user exists in database:
   ```bash
   mysql -u root avipro_travels -e "SELECT * FROM admin_users;"
   ```

2. Reset admin password (if needed):
   ```php
   <?php
   require_once 'config/database.php';
   $new_password = password_hash('admin123', PASSWORD_DEFAULT);
   $conn = getDBConnection();
   $conn->query("UPDATE admin_users SET password = '$new_password' WHERE username = 'admin'");
   echo "Password reset!";
   ?>
   ```

### If CSS Not Loading:
- Check browser console for 404 errors
- Verify BASE_URL in config/config.php is correct
- Clear browser cache

---

## 📋 Admin Panel Pages

After login, you can access:
- **Dashboard**: http://localhost:8000/admin/index.php
- **Packages**: http://localhost:8000/admin/packages.php
- **Bookings**: http://localhost:8000/admin/bookings.php
- **Content**: http://localhost:8000/admin/content.php
- **Contact**: http://localhost:8000/admin/contact.php

---

## ✅ Quick Test

1. Open browser
2. Go to: http://localhost:8000/admin/login.php
3. Enter username: `admin`
4. Enter password: `admin123`
5. Click "Login"
6. You should see the Admin Dashboard!

---

**Note**: If you're already logged in, visiting http://localhost:8000/admin/ will redirect you to the dashboard automatically.

