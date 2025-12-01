# Avipro Travels - Quick Start Guide

## 🚀 5-Minute Setup

### Step 1: Database Setup (2 minutes)
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE avipro_travels;"

# Import schema
mysql -u root -p avipro_travels < database.sql

# (Optional) Add sample data
mysql -u root -p avipro_travels < sample-data.sql
```

### Step 2: Configuration (1 minute)
1. Open `config/database.php`
2. Update credentials if needed (default: root, no password)
3. Open `config/config.php`
4. Update BASE_URL if different from `http://localhost/iwpproject/`

### Step 3: Permissions (1 minute)
```bash
chmod 755 uploads/
chmod 755 uploads/packages/
chmod 755 uploads/banners/
```

### Step 4: Verify Installation (1 minute)
1. Open browser: `http://localhost/iwpproject/verify-installation.php`
2. Check for any errors
3. Fix any issues shown

### Step 5: Access the System
- **Frontend**: `http://localhost/iwpproject/`
- **Admin**: `http://localhost/iwpproject/admin/`
- **Login**: admin / admin123

---

## ✅ Verification Checklist

After setup, verify:
- [ ] Frontend homepage loads
- [ ] Admin login works
- [ ] Can add a package
- [ ] Booking form works
- [ ] Images upload correctly

---

## 🆘 Quick Troubleshooting

**Database Error?**
- Check MySQL is running
- Verify credentials in `config/database.php`
- Ensure database exists

**Images Not Uploading?**
- Check `uploads/` directory permissions
- Verify PHP upload settings in `php.ini`

**Pages Not Loading?**
- Check BASE_URL in `config/config.php`
- Verify Apache/Nginx is running
- Check PHP errors in logs

---

## 📞 Need Help?

1. Check `README.md` for detailed documentation
2. Review `INSTALLATION_GUIDE.md` for step-by-step instructions
3. Run `verify-installation.php` to check setup

---

**Ready to go!** 🎉

