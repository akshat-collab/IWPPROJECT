# Avipro Travels - CMS Based Travel Package Website

A dynamic Content Management System (CMS) based travel package website built with PHP, MySQL, JavaScript, and AJAX.

## Project Overview

Avipro Travels is a complete travel package management system that allows administrators to manage travel packages, bookings, and site content through an intuitive admin panel. The frontend provides a beautiful, responsive interface for customers to browse packages and submit booking enquiries.

## Features

### Frontend Features
- **Home Page** - Featured packages showcase with hero banner
- **About Us** - Company information and mission
- **Tour Packages** - Browse all available travel packages with search and filter
- **Package Details** - Detailed view of each package with itinerary and inclusions
- **Booking/Enquiry Page** - AJAX-powered booking form with JavaScript validation
- **Contact Us** - Contact information and inquiry form

### Admin Panel Features
- **Admin Login** - Secure login system with session management
- **Dashboard** - Statistics and recent bookings overview
- **Package Management** - Add, edit, delete travel packages with image upload
- **Booking Management** - View and manage booking enquiries with status updates
- **Content Management** - Update site content (banners, about page, etc.)
- **Contact Information** - Manage contact details and social media links

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla JS)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **AJAX**: Fetch API for asynchronous form submission
- **Session Management**: PHP Sessions

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- phpMyAdmin (optional, for database management)

### Step 1: Database Setup

1. Create a MySQL database named `avipro_travels`
2. Import the database schema:
   ```sql
   mysql -u root -p avipro_travels < database.sql
   ```
   Or use phpMyAdmin to import the `database.sql` file

### Step 2: Configuration

1. Open `config/database.php` and update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'avipro_travels');
   ```

2. Open `config/config.php` and update the BASE_URL if your project is in a different location:
   ```php
   define('BASE_URL', 'http://localhost/iwpproject/');
   ```

### Step 3: File Permissions

Ensure the uploads directory is writable:
```bash
chmod 755 uploads/
chmod 755 uploads/packages/
chmod 755 uploads/banners/
```

### Step 4: Access the Website

1. **Frontend**: Navigate to `http://localhost/iwpproject/`
2. **Admin Panel**: Navigate to `http://localhost/iwpproject/admin/`

## Admin Credentials

**Default Admin Login:**
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **Important**: Change the default password after first login for security!

## Project Structure

```
iwpproject/
├── admin/                  # Admin panel files
│   ├── includes/           # Header, sidebar includes
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login page
│   ├── packages.php        # Package management
│   ├── package-form.php    # Add/Edit package form
│   ├── bookings.php        # Booking management
│   ├── content.php         # Site content management
│   └── contact.php         # Contact info management
├── api/                    # API endpoints
│   └── booking-submit.php  # AJAX booking submission handler
├── assets/                 # Static assets
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Images (create if needed)
├── config/                 # Configuration files
│   ├── config.php         # Site configuration
│   └── database.php       # Database connection
├── includes/              # Frontend includes
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── uploads/               # Uploaded files
│   ├── packages/         # Package images
│   └── banners/          # Banner images
├── index.php             # Home page
├── about.php             # About us page
├── packages.php         # Packages listing
├── package-details.php  # Package details page
├── booking.php          # Booking form
├── contact.php          # Contact page
├── database.sql         # Database schema
└── README.md            # This file
```

## Key Features Implementation

### JavaScript Validation
- Real-time field validation
- Email format validation
- Phone number validation
- Date validation (future dates only)
- Required field validation

### AJAX Form Submission
- Asynchronous form submission without page reload
- Success/error message display
- Form reset on successful submission

### Session Management
- Admin session timeout (30 minutes)
- Secure session handling
- Automatic logout on session expiry

### Image Upload
- Package image upload with validation
- Banner image upload
- Automatic file naming
- Image preview in admin panel

## Database Schema

The database includes the following tables:
- `admin_users` - Admin user accounts
- `packages` - Travel packages
- `bookings` - Booking enquiries
- `site_content` - CMS-managed site content
- `contact_info` - Contact information

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Security Features

- Password hashing using PHP `password_hash()`
- SQL injection prevention using prepared statements
- XSS protection with `htmlspecialchars()`
- Session-based authentication
- Input sanitization

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Image Upload Not Working**
   - Check file permissions on `uploads/` directory
   - Verify PHP `upload_max_filesize` and `post_max_size` settings
   - Ensure directory exists

3. **Session Not Working**
   - Check PHP session configuration
   - Ensure `session_start()` is called
   - Verify session directory is writable

4. **AJAX Request Failing**
   - Check browser console for errors
   - Verify API endpoint URL is correct
   - Check PHP error logs

## Development Notes

- All user inputs are sanitized before database insertion
- Passwords are hashed using PHP's `password_hash()` function
- File uploads are validated for type and size
- Error messages are user-friendly and informative

## Future Enhancements

Potential features for future development:
- Email notifications for bookings
- Payment gateway integration
- User registration and login
- Package reviews and ratings
- Multi-language support
- Advanced search filters

## Support

For issues or questions, please contact the development team.

## License

This project is developed for educational purposes as part of the IWP (Internet and Web Programming) course project.

---

**Project Submission Date**: December 5, 2025
**Marks**: 20 (Internal Assessment)

# IWPPROJECT
