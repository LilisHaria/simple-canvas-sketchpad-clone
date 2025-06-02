
-- Database: arenakuy
CREATE DATABASE IF NOT EXISTS arenakuy;
USE arenakuy;

-- Tabel users (untuk admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel pelanggan (untuk user yang sign up)
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel lapangan
CREATE TABLE lapangan (
    id_lapangan INT AUTO_INCREMENT PRIMARY KEY,
    nama_lapangan VARCHAR(100) NOT NULL,
    tipe ENUM('Indoor', 'Outdoor', 'Synthetic') NOT NULL,
    harga DECIMAL(10,2) NOT NULL
);

-- Tabel jadwal
CREATE TABLE jadwal (
    id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    status ENUM('available', 'unavailable') DEFAULT 'available'
);

-- Tabel booking
CREATE TABLE booking (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_lapangan INT NOT NULL,
    id_jadwal INT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_lapangan) REFERENCES lapangan(id_lapangan),
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);

-- Tabel detail_booking
CREATE TABLE detail_booking (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    durasi INT NOT NULL,
    catatan TEXT,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking)
);

-- Tabel log_booking
CREATE TABLE log_booking (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking)
);

-- Insert data sample admin (password: admin123)
INSERT INTO users (username, email, password, full_name) VALUES 
('admin', 'admin@arenakuy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Insert data sample lapangan
INSERT INTO lapangan (nama_lapangan, tipe, harga) VALUES 
('Lapangan Indoor A', 'Indoor', 150000),
('Lapangan Indoor B', 'Indoor', 150000),
('Lapangan Outdoor A', 'Outdoor', 100000),
('Lapangan Outdoor B', 'Outdoor', 100000),
('Lapangan Synthetic A', 'Synthetic', 200000),
('Lapangan Synthetic B', 'Synthetic', 200000);

-- Insert data sample jadwal
INSERT INTO jadwal (jam_mulai, jam_selesai, status) VALUES 
('08:00:00', '10:00:00', 'available'),
('10:00:00', '12:00:00', 'available'),
('12:00:00', '14:00:00', 'available'),
('14:00:00', '16:00:00', 'available'),
('16:00:00', '18:00:00', 'available'),
('18:00:00', '20:00:00', 'available'),
('20:00:00', '22:00:00', 'available');
