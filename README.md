
# ArenaKuy! - Platform Booking Lapangan Futsal

## Deskripsi
ArenaKuy! adalah platform booking lapangan futsal online yang memudahkan pengguna untuk mencari dan memesan lapangan futsal di Indonesia.

## Fitur Utama
- **Registrasi & Login**: Sistem autentikasi untuk pelanggan
- **Dashboard**: Informasi ringkas booking dan statistik
- **Cari Arena**: Pencarian lapangan berdasarkan nama dan tipe
- **My Booking**: Melihat booking aktif dan mendatang
- **History**: Riwayat booking yang telah selesai
- **Booking System**: Proses booking dengan multiple table insert

## Struktur Database
- `users`: Admin users
- `pelanggan`: Customer/pelanggan yang melakukan booking
- `lapangan`: Data lapangan futsal
- `jadwal`: Waktu slot yang tersedia
- `booking`: Data booking utama
- `detail_booking`: Detail booking (durasi, catatan)
- `log_booking`: Log waktu booking dilakukan

## Teknologi
- **Frontend**: HTML5, CSS3 (Bootstrap 5), JavaScript
- **Backend**: PHP 8+
- **Database**: MySQL
- **Icons**: Font Awesome
- **Server**: XAMPP (Apache + MySQL)

## Instalasi

### Persyaratan
- XAMPP atau server lokal dengan PHP 7.4+ dan MySQL
- Web browser modern

### Langkah Instalasi
1. **Download dan ekstrak** file project ke folder `htdocs` XAMPP
   ```
   C:\xampp\htdocs\arenakuy\
   ```

2. **Import Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru bernama `arenakuy`
   - Import file `database/arenakuy.sql`

3. **Konfigurasi Database**
   - Buka file `config/koneksi.php`
   - Sesuaikan konfigurasi database jika diperlukan:
     ```php
     $host = 'localhost';
     $dbname = 'arenakuy';
     $username = 'root';
     $password = '';
     ```

4. **Jalankan Aplikasi**
   - Start Apache dan MySQL di XAMPP Control Panel
   - Buka browser dan akses: `http://localhost/arenakuy`

## Struktur Folder
```
arenakuy/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/
│   └── koneksi.php
├── database/
│   └── arenakuy.sql
├── includes/
│   └── navbar.php
├── index.php
├── dashboard.php
├── search.php
├── my-booking.php
├── history.php
├── process-booking.php
└── README.md
```

## Cara Penggunaan

### Untuk Pelanggan:
1. **Daftar/Login**: Buat akun baru atau login dengan akun existing
2. **Cari Arena**: Gunakan fitur pencarian untuk menemukan lapangan
3. **Booking**: Pilih lapangan, tanggal, waktu, dan durasi
4. **Konfirmasi**: Sistem akan menyimpan booking dan menampilkan konfirmasi
5. **Kelola Booking**: Lihat booking aktif di halaman "My Booking"
6. **Riwayat**: Cek riwayat booking di halaman "History"

### Untuk Admin:
- Login dengan akun admin (username: admin, password: admin123)
- Kelola data lapangan, jadwal, dan monitoring booking

## Fitur Teknis

### Database Relations
- `booking` → `pelanggan` (Many to One)
- `booking` → `lapangan` (Many to One)  
- `booking` → `jadwal` (Many to One)
- `detail_booking` → `booking` (One to One)
- `log_booking` → `booking` (One to Many)

### Security Features
- Password hashing dengan `password_hash()`
- Input sanitization untuk mencegah SQL injection
- Session management untuk autentikasi
- CSRF protection (dapat ditambahkan)

### Key Functions
- **Autentikasi**: Login/register dengan validasi
- **Booking Process**: Multi-table insert dengan transaction
- **Search**: Dynamic search dengan filter
- **History**: Join multiple tables untuk data lengkap

## Pengembangan Lanjutan

### TODO List:
- [ ] Sistem pembayaran online
- [ ] Notifikasi email/SMS
- [ ] Rating dan review lapangan
- [ ] Upload foto lapangan
- [ ] Mobile responsive improvement
- [ ] Admin panel yang lebih lengkap
- [ ] API untuk mobile app

### Optimisasi:
- Index database untuk query performance
- Caching sistem untuk data yang sering diakses
- Image optimization dan CDN
- Error logging dan monitoring

## Support
Untuk pertanyaan atau bantuan, silakan hubungi developer.

## License
© 2024 ArenaKuy! - Educational Project
