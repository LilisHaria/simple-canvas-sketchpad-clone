
-- Create database
CREATE DATABASE IF NOT EXISTS arenakuy_db;
USE arenakuy_db;

-- Create lapangan table
CREATE TABLE IF NOT EXISTS lapangan (
    id_lapangan INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_lapangan VARCHAR(100) NOT NULL,
    lokasi VARCHAR(255) DEFAULT NULL,
    harga_per_jam DECIMAL(10,2) DEFAULT NULL
);

-- Create pelanggan table
CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(15) DEFAULT NULL
);

-- Create booking table
CREATE TABLE IF NOT EXISTS booking (
    id_booking INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_pelanggan INT(11) NOT NULL,
    tanggal_booking DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan)
);

-- Insert sample data for lapangan
INSERT INTO lapangan (nama_lapangan, lokasi, harga_per_jam) VALUES
('Arena Indoor', 'Jakarta Selatan', 150000),
('Arena Outdoor', 'Jakarta Barat', 100000),
('Arena Synthetic', 'Jakarta Utara', 120000),
('Lapangan Futsal Bintaro', 'Tangerang Selatan', 130000),
('GOR Senayan', 'Jakarta Pusat', 200000);

-- Insert sample data for pelanggan
INSERT INTO pelanggan (nama, email, no_telepon) VALUES
('John Doe', 'john@example.com', '081234567890'),
('Jane Smith', 'jane@example.com', '081234567891');
