-- Avipro Travels Database Schema
-- Created for CMS-based Travel Package Website

CREATE DATABASE IF NOT EXISTS avipro_travels;
USE avipro_travels;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Travel Packages Table
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    destination VARCHAR(100) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2) DEFAULT NULL,
    description TEXT NOT NULL,
    itinerary TEXT,
    inclusions TEXT,
    exclusions TEXT,
    image VARCHAR(255) DEFAULT NULL,
    gallery_images TEXT DEFAULT NULL,
    featured TINYINT(1) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings/Enquiries Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    travel_date DATE NOT NULL,
    number_of_persons INT NOT NULL,
    message TEXT,
    package_id INT DEFAULT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site Content Table (For CMS management)
CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(100) UNIQUE NOT NULL,
    content_type ENUM('text', 'html', 'image', 'banner') DEFAULT 'text',
    content_value TEXT,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact Information Table
CREATE TABLE IF NOT EXISTS contact_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(100),
    whatsapp VARCHAR(50),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    twitter VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin User (username: admin, password: admin123)
-- Password is hashed using password_hash() - default password: admin123
INSERT INTO admin_users (username, password, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@aviprotravels.com', 'Administrator');

-- Insert Default Contact Information
INSERT INTO contact_info (address, phone, email, whatsapp) VALUES
('123 Travel Street, Tourism City, TC 12345', '+1-234-567-8900', 'info@aviprotravels.com', '+1-234-567-8900');

-- Insert Default Site Content
INSERT INTO site_content (content_key, content_type, content_value, description) VALUES
('home_banner_title', 'text', 'Discover Amazing Travel Packages', 'Homepage Banner Title'),
('home_banner_subtitle', 'text', 'Explore the world with Avipro Travels', 'Homepage Banner Subtitle'),
('about_us_content', 'html', '<p>Avipro Travels is a leading travel agency dedicated to providing exceptional travel experiences. We offer carefully curated travel packages to stunning destinations around the world.</p>', 'About Us Page Content'),
('home_featured_text', 'text', 'Featured Travel Packages', 'Featured Packages Section Title');

