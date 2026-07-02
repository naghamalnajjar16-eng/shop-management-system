CREATE DATABASE IF NOT EXISTS shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE shop_db;

-- جدول الادمن
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- إدخال بيانات الادمن
INSERT INTO admin (username, password) VALUES 
('admin', 'admin123');

-- جدول العملاء
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    address TEXT
);

-- جدول المنتجات (معدّل)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,  -- Changed from category_id to category name
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT
);

-- بيانات العملاء
INSERT INTO customers (name, email, phone, address) VALUES
('أحمد علي', 'ahmed@example.com', '0591234567', 'غزة - شارع عمر المختار'),
('سارة محمود', 'sara@example.com', '0597654321', 'القدس - حي المصرارة'),
('ليلى حسن', 'leila@example.com', '0599876543', 'رام الله - البيرة');

-- بيانات المنتجات (معدّل)
INSERT INTO products (name, category, price, stock, description) VALUES
('هاتف ذكي', 'إلكترونيات', 1500.00, 10, 'هاتف ذكي بشاشة 6 بوصة وكاميرا مزدوجة'),
('لابتوب', 'إلكترونيات', 3500.00, 5, 'لابتوب بمعالج Core i5 وذاكرة 8GB'),
('تيشيرت', 'ملابس', 80.00, 50, 'تيشيرت قطني مريح'),
('طاولة', 'أثاث', 1200.00, 8, 'طاولة خشبية متينة'),
('رواية', 'كتب', 45.00, 30, 'رواية أدبية مشوقة');