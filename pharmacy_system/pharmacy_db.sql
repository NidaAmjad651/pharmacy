-- =====================================================
-- PHARMACY SALE & PURCHASE MANAGEMENT SYSTEM DATABASE
-- =====================================================

DROP DATABASE IF EXISTS pharmacy_db;
CREATE DATABASE pharmacy_db;
USE pharmacy_db;

-- =====================================================
-- 1. USERS TABLE (Login / Admin / Staff)
-- =====================================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin','Manager','Cashier') DEFAULT 'Cashier',
    contact_no VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 2. CATEGORY TABLE (Medicine Category)
-- =====================================================
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- =====================================================
-- 3. SUPPLIER TABLE
-- =====================================================
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(100),
    contact_no VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 4. CUSTOMER TABLE
-- =====================================================
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    contact_no VARCHAR(20),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 5. MEDICINE / PRODUCT TABLE
-- =====================================================
CREATE TABLE medicines (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(150) NOT NULL,
    category_id INT,
    supplier_id INT,
    batch_no VARCHAR(50),
    unit VARCHAR(30) DEFAULT 'Piece',
    purchase_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    sale_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantity INT NOT NULL DEFAULT 0,
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id) ON DELETE SET NULL
);

-- =====================================================
-- 6. PURCHASE TABLE (Purchase Invoice - Header)
-- =====================================================
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    user_id INT,
    purchase_date DATE NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- =====================================================
-- 7. PURCHASE ITEMS TABLE (Purchase Invoice - Detail)
-- =====================================================
CREATE TABLE purchase_items (
    purchase_item_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL,
    purchase_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (purchase_id) REFERENCES purchases(purchase_id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- =====================================================
-- 8. SALES TABLE (Sale Invoice - Header)
-- =====================================================
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    user_id INT,
    sale_date DATE NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    paid_amount DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- =====================================================
-- 9. SALE ITEMS TABLE (Sale Invoice - Detail)
-- =====================================================
CREATE TABLE sale_items (
    sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL,
    sale_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(sale_id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- =====================================================
-- TRIGGERS: Auto update stock on purchase & sale
-- =====================================================
DELIMITER //
CREATE TRIGGER trg_after_purchase_item_insert
AFTER INSERT ON purchase_items
FOR EACH ROW
BEGIN
    UPDATE medicines
    SET quantity = quantity + NEW.quantity
    WHERE medicine_id = NEW.medicine_id;
END//

CREATE TRIGGER trg_after_sale_item_insert
AFTER INSERT ON sale_items
FOR EACH ROW
BEGIN
    UPDATE medicines
    SET quantity = quantity - NEW.quantity
    WHERE medicine_id = NEW.medicine_id;
END//
DELIMITER ;

-- =====================================================
-- SAMPLE DATA
-- =====================================================

INSERT INTO users (full_name, username, password, role, contact_no) VALUES
('Admin User', 'admin', 'admin123', 'Admin', '03000000000'),
('Ali Cashier', 'ali', 'ali123', 'Cashier', '03111111111');

INSERT INTO categories (category_name) VALUES
('Tablet'), ('Syrup'), ('Injection'), ('Capsule'), ('Ointment');

INSERT INTO suppliers (supplier_name, company_name, contact_no, email, address) VALUES
('Ahmed Traders', 'GetZ Pharma', '03211234567', 'ahmed@getz.com', 'Karachi'),
('Bilal Distributors', 'Abbott Labs', '03221234567', 'bilal@abbott.com', 'Lahore');

INSERT INTO customers (customer_name, contact_no, address) VALUES
('Walk-in Customer', '0000000000', 'N/A'),
('Sara Khan', '03451234567', 'Islamabad');

INSERT INTO medicines (medicine_name, category_id, supplier_id, batch_no, unit, purchase_price, sale_price, quantity, expiry_date) VALUES
('Panadol 500mg', 1, 1, 'B101', 'Strip', 20.00, 30.00, 0, '2027-05-01'),
('Augmentin Syrup', 2, 2, 'B102', 'Bottle', 150.00, 200.00, 0, '2026-12-01'),
('Brufen 400mg', 1, 1, 'B103', 'Strip', 35.00, 50.00, 0, '2027-01-15');

-- Sample Purchase (this will auto increase stock via trigger)
INSERT INTO purchases (supplier_id, user_id, purchase_date, total_amount) VALUES
(1, 1, CURDATE(), 5500.00);

INSERT INTO purchase_items (purchase_id, medicine_id, quantity, purchase_price, subtotal) VALUES
(1, 1, 100, 20.00, 2000.00),
(1, 3, 100, 35.00, 3500.00);

-- Sample Sale (this will auto decrease stock via trigger)
INSERT INTO sales (customer_id, user_id, sale_date, total_amount, discount, paid_amount) VALUES
(2, 2, CURDATE(), 800.00, 0, 800.00);

INSERT INTO sale_items (sale_id, medicine_id, quantity, sale_price, subtotal) VALUES
(1, 1, 10, 30.00, 300.00),
(1, 3, 10, 50.00, 500.00);

-- =====================================================
-- CHECK RESULTS
-- =====================================================
SELECT * FROM medicines;
SELECT * FROM purchases;
SELECT * FROM sales;
