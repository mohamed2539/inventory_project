-- إنشاء قاعدة البيانات
CREATE DATABASE inventory_management_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_management_db;

-- إنشاء جدول الفروع أولاً لأنه مرتبط بجداول أخرى
CREATE TABLE branches (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(100) NOT NULL UNIQUE,
                          address VARCHAR(255) NOT NULL,
                          phone VARCHAR(20) NOT NULL,
                          email VARCHAR(100) NOT NULL UNIQUE,
                          manager_name VARCHAR(100) NOT NULL,
                          status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                          notes TEXT DEFAULT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- إنشاء جدول المستخدمين بعد الفروع
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       full_name VARCHAR(100) NOT NULL,
                       branch_id INT DEFAULT NULL,
                       role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                       status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL
);

-- إنشاء جدول الموردين
CREATE TABLE suppliers (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           name VARCHAR(100) NOT NULL UNIQUE,
                           phone VARCHAR(20) NOT NULL,
                           email VARCHAR(100) NOT NULL UNIQUE,
                           address VARCHAR(255) NOT NULL,
                           contact_person VARCHAR(100) NOT NULL,
                           tax_number VARCHAR(50) DEFAULT NULL,
                           commercial_record VARCHAR(50) DEFAULT NULL,
                           payment_terms VARCHAR(255) DEFAULT NULL,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           is_deleted TINYINT(1) NOT NULL DEFAULT 0
);

-- إنشاء جدول المواد
CREATE TABLE materials (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           code VARCHAR(50) NOT NULL UNIQUE,
                           barcode VARCHAR(100) DEFAULT NULL,
                           name VARCHAR(100) NOT NULL,
                           unit ENUM('piece', 'kg', 'liter') NOT NULL DEFAULT 'piece',
                           quantity INT NOT NULL DEFAULT 0,
                           min_quantity INT NOT NULL DEFAULT 1,
                           max_quantity INT NOT NULL DEFAULT 100,
                           price DECIMAL(10,2) NOT NULL,
                           branch_id INT DEFAULT NULL,
                           supplier_id INT DEFAULT NULL,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           deleted_at TIMESTAMP NULL DEFAULT NULL,
                           FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
                           FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

-- إنشاء جدول العمليات (الإضافة والصرف)
CREATE TABLE transactions (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              material_id INT NOT NULL,
                              user_id INT NOT NULL,
                              type ENUM('add', 'dispense') NOT NULL,
                              quantity INT NOT NULL,
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                              FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
                              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- إنشاء جدول سجل التعديلات
CREATE TABLE logs (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      user_id INT NOT NULL,
                      action ENUM('add', 'update', 'delete') NOT NULL,
                      table_name VARCHAR(50) NOT NULL,
                      record_id INT NOT NULL,
                      details TEXT,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
