
-- Database ArenaKuy
CREATE DATABASE IF NOT EXISTS arenakuy;
USE arenakuy;

-- Tabel users (untuk admin)
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel pelanggan
CREATE TABLE pelanggan (
    id_pelanggan INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_hp VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel lapangan
CREATE TABLE lapangan (
    id_lapangan INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_lapangan VARCHAR(100) NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    harga INT(11) NOT NULL
);

-- Tabel jadwal
CREATE TABLE jadwal (
    id_jadwal INT(11) PRIMARY KEY AUTO_INCREMENT,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'available'
);

-- Tabel booking
CREATE TABLE booking (
    id_booking INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_pelanggan INT(11) NOT NULL,
    id_lapangan INT(11) NOT NULL,
    id_jadwal INT(11) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_lapangan) REFERENCES lapangan(id_lapangan),
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);

-- Tabel detail_booking
CREATE TABLE detail_booking (
    id_detail INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_booking INT(11) NOT NULL,
    durasi INT(11) NOT NULL,
    catatan TEXT,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking)
);

-- Tabel log_booking
CREATE TABLE log_booking (
    id_log INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_booking INT(11) NOT NULL,
    waktu DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking)
);

-- Insert sample data
INSERT INTO lapangan (nama_lapangan, tipe, harga) VALUES
('Arena Indoor Premium', 'Indoor', 150000),
('Arena Outdoor Natural', 'Outdoor', 100000),
('Arena Synthetic Modern', 'Synthetic', 120000),
('Lapangan Futsal Bintaro', 'Indoor', 130000),
('GOR Senayan', 'Indoor', 200000);

INSERT INTO jadwal (jam_mulai, jam_selesai, status) VALUES
('06:00:00', '08:00:00', 'available'),
('08:00:00', '10:00:00', 'available'),
('10:00:00', '12:00:00', 'available'),
('13:00:00', '15:00:00', 'available'),
('15:00:00', '17:00:00', 'available'),
('17:00:00', '19:00:00', 'available'),
('19:00:00', '21:00:00', 'available'),
('21:00:00', '23:00:00', 'available');

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password, full_name) VALUES
('admin', 'admin@arenakuy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');
