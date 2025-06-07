
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

-- Tabel tambahan untuk sistem yang lebih lengkap
CREATE TABLE arenas (
    arena_id INT AUTO_INCREMENT PRIMARY KEY,
    arena_name VARCHAR(100) NOT NULL,
    location VARCHAR(200) NOT NULL,
    description TEXT,
    price_per_hour DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    arena_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (arena_id) REFERENCES arenas(arena_id)
);

CREATE TABLE logbooking (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_status VARCHAR(20),
    new_status VARCHAR(20),
    changed_by INT,
    change_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
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

-- Insert data sample arena
INSERT INTO arenas (arena_name, location, description, price_per_hour, is_active) VALUES 
('Arena Indoor A', 'Jl. Sudirman No. 123, Jakarta', 'Lapangan futsal indoor berkualitas tinggi dengan AC dan sound system', 150000, TRUE),
('Arena Indoor B', 'Jl. Thamrin No. 456, Jakarta', 'Lapangan futsal indoor modern dengan fasilitas lengkap', 150000, TRUE),
('Arena Outdoor A', 'Jl. Gatot Subroto No. 789, Jakarta', 'Lapangan futsal outdoor dengan rumput sintetis berkualitas', 100000, TRUE),
('Arena Outdoor B', 'Jl. Kuningan No. 321, Jakarta', 'Lapangan futsal outdoor luas dengan pencahayaan malam', 100000, TRUE),
('Arena Synthetic A', 'Jl. Senopati No. 654, Jakarta', 'Lapangan futsal dengan rumput sintetis premium dan tribun', 200000, TRUE),
('Arena Synthetic B', 'Jl. Kemang No. 987, Jakarta', 'Lapangan futsal synthetic dengan standar FIFA', 200000, TRUE);

-- Insert data sample jadwal
INSERT INTO jadwal (jam_mulai, jam_selesai, status) VALUES 
('08:00:00', '10:00:00', 'available'),
('10:00:00', '12:00:00', 'available'),
('12:00:00', '14:00:00', 'available'),
('14:00:00', '16:00:00', 'available'),
('16:00:00', '18:00:00', 'available'),
('18:00:00', '20:00:00', 'available'),
('20:00:00', '22:00:00', 'available');

-- ===== TRIGGERS =====

-- 1. Trigger untuk Insert Booking (Lebih Robust)
DELIMITER //
CREATE TRIGGER after_booking_insert
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    DECLARE user_exists INT;
    DECLARE arena_exists INT;
    
    -- Validasi referensi user dan arena
    SELECT COUNT(*) INTO user_exists FROM users WHERE id = NEW.user_id;
    SELECT COUNT(*) INTO arena_exists FROM arenas WHERE arena_id = NEW.arena_id;
    
    IF user_exists > 0 AND arena_exists > 0 THEN
        INSERT INTO logbooking (
            booking_id, 
            action, 
            new_status, 
            changed_by, 
            change_note
        ) VALUES (
            NEW.booking_id,
            'create',
            NEW.status,
            NEW.user_id,
            CONCAT('Booking baru dibuat untuk ', 
                  (SELECT arena_name FROM arenas WHERE arena_id = NEW.arena_id),
                  ' pada ', NEW.booking_date, ' ', NEW.start_time)
        );
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Referensi user atau arena tidak valid';
    END IF;
END //
DELIMITER ;

-- 2. Trigger untuk Update Booking (Lebih Lengkap)
DELIMITER //
CREATE TRIGGER after_booking_update
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    DECLARE log_message TEXT;
    
    -- Log perubahan status
    IF NEW.status <> OLD.status THEN
        INSERT INTO logbooking (
            booking_id, 
            action, 
            old_status, 
            new_status, 
            changed_by,
            change_note
        ) VALUES (
            NEW.booking_id,
            'status_change',
            OLD.status,
            NEW.status,
            NEW.user_id,
            CONCAT('Status berubah dari ', OLD.status, ' ke ', NEW.status)
        );
    END IF;
    
    -- Log perubahan tanggal/waktu booking
    IF NEW.booking_date <> OLD.booking_date OR NEW.start_time <> OLD.start_time OR NEW.end_time <> OLD.end_time THEN
        SET log_message = CONCAT('Jadwal diubah dari ', 
                              OLD.booking_date, ' ', OLD.start_time, '-', OLD.end_time,
                              ' menjadi ', 
                              NEW.booking_date, ' ', NEW.start_time, '-', NEW.end_time);
                              
        INSERT INTO logbooking (
            booking_id,
            action,
            changed_by,
            change_note
        ) VALUES (
            NEW.booking_id,
            'schedule_change',
            NEW.user_id,
            log_message
        );
    END IF;
    
    -- Log perubahan lainnya
    IF NEW.arena_id <> OLD.arena_id THEN
        INSERT INTO logbooking (
            booking_id,
            action,
            changed_by,
            change_note
        ) VALUES (
            NEW.booking_id,
            'arena_change',
            NEW.user_id,
            CONCAT('Lapangan diubah dari ', 
                  (SELECT arena_name FROM arenas WHERE arena_id = OLD.arena_id),
                  ' ke ',
                  (SELECT arena_name FROM arenas WHERE arena_id = NEW.arena_id))
        );
    END IF;
END //
DELIMITER ;

-- ===== VIEWS =====

-- 1. Active Bookings View (Tanpa Parameter)
CREATE VIEW active_bookings_view AS
SELECT 
    b.booking_id,
    b.user_id,
    a.arena_name,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.duration,
    b.total_price,
    b.status,
    DATEDIFF(b.booking_date, CURDATE()) AS days_remaining
FROM 
    bookings b
JOIN 
    arenas a ON b.arena_id = a.arena_id
WHERE 
    b.booking_date >= CURDATE()
ORDER BY 
    b.booking_date, b.start_time;

-- 2. Booking History View (Tanpa Parameter)
CREATE VIEW booking_history_view AS
SELECT 
    b.booking_id,
    b.user_id,
    a.arena_name,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,
    b.total_price,
    (SELECT COUNT(*) FROM logbooking l WHERE l.booking_id = b.booking_id) AS log_count
FROM 
    bookings b
JOIN 
    arenas a ON b.arena_id = a.arena_id
ORDER BY 
    b.booking_date DESC, b.start_time DESC;

-- 3. Admin Dashboard View
CREATE VIEW admin_dashboard_view AS
SELECT 
    COUNT(DISTINCT u.id) AS total_users,
    COUNT(DISTINCT a.arena_id) AS total_arenas,
    SUM(CASE WHEN b.booking_date >= CURDATE() THEN 1 ELSE 0 END) AS upcoming_bookings,
    SUM(CASE WHEN b.status = 'confirmed' THEN b.total_price ELSE 0 END) AS total_revenue
FROM 
    users u
CROSS JOIN 
    arenas a
LEFT JOIN 
    bookings b ON 1=1;
