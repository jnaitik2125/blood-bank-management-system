CREATE DATABASE IF NOT EXISTS blood_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blood_inventory;

DROP TABLE IF EXISTS inventory_logs;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS blood_units;
DROP TABLE IF EXISTS donors;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'receptionist', 'collection_staff') NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL
);

CREATE TABLE donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    age INT NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    contact VARCHAR(30),
    address TEXT,
    last_donation_date DATE NULL
);

CREATE TABLE blood_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group VARCHAR(5) NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    collection_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('available', 'used', 'expired') NOT NULL DEFAULT 'available'
);

CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    collection_staff_id INT NOT NULL,
    date DATE NOT NULL,
    CONSTRAINT fk_donation_donor FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
    CONSTRAINT fk_donation_staff FOREIGN KEY (collection_staff_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(120) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    requested_by INT NOT NULL,
    date DATE NOT NULL,
    CONSTRAINT fk_request_user FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE inventory_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(120) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    performed_by INT NOT NULL,
    timestamp DATETIME NOT NULL,
    CONSTRAINT fk_log_user FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE RESTRICT
);

INSERT INTO users (name, email, password, role, status, created_at) VALUES
('Admin User', 'admin@blood.local', '$2y$10$6Gl4sR5Xpx/p4nMsD5S/gumuGo/GxYN6QlJy9xu7EV/DsNS1Tf9o2', 'admin', 1, NOW()),
('Reception User', 'reception@blood.local', '$2y$10$6Gl4sR5Xpx/p4nMsD5S/gumuGo/GxYN6QlJy9xu7EV/DsNS1Tf9o2', 'receptionist', 1, NOW()),
('Collector User', 'collector@blood.local', '$2y$10$6Gl4sR5Xpx/p4nMsD5S/gumuGo/GxYN6QlJy9xu7EV/DsNS1Tf9o2', 'collection_staff', 1, NOW());

INSERT INTO donors (name, age, gender, blood_group, contact, address, last_donation_date) VALUES
('Rahul Sharma', 28, 'male', 'B+', '9876543210', 'Delhi', '2026-01-15'),
('Sneha Verma', 31, 'female', 'O+', '9876543211', 'Noida', '2026-02-10');

INSERT INTO blood_units (blood_group, quantity, collection_date, expiry_date, status) VALUES
('B+', 2.00, '2026-03-01', '2026-04-05', 'available'),
('O+', 1.50, '2026-03-02', '2026-04-06', 'available');

INSERT INTO donations (donor_id, blood_group, quantity, collection_staff_id, date) VALUES
(1, 'B+', 1.00, 3, '2026-03-01'),
(2, 'O+', 1.00, 3, '2026-03-02');

INSERT INTO requests (patient_name, blood_group, quantity, status, requested_by, date) VALUES
('Patient A', 'B+', 1.00, 'pending', 2, '2026-03-10'),
('Patient B', 'O+', 0.50, 'approved', 2, '2026-03-11');

INSERT INTO inventory_logs (action, blood_group, quantity, performed_by, timestamp) VALUES
('seed_stock', 'B+', 2.00, 1, NOW()),
('seed_stock', 'O+', 1.50, 1, NOW());

