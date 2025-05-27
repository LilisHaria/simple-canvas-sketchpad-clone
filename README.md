
# ArenaKuy! - Futsal Field Booking Platform

## Setup Instructions

### 1. Database Setup
1. Import the `database.sql` file into your MySQL database
2. Update database credentials in `config.php`

### 2. File Structure
```
/
├── index.html          # Homepage
├── signin.html         # Sign in page
├── signup.html         # Sign up page
├── styles.css          # Custom styles
├── script.js           # JavaScript functionality
├── config.php          # Database configuration
├── auth.php            # Authentication handler
├── register.php        # Registration handler
├── search.php          # Search functionality
├── database.sql        # Database schema and sample data
└── README.md           # This file
```

### 3. Features
- Responsive design with Bootstrap 5
- User registration and login
- Search functionality for futsal fields
- Sidebar navigation
- Mobile-friendly interface

### 4. Database Tables
- `lapangan` - Futsal field information
- `pelanggan` - Customer information  
- `booking` - Booking records

### 5. Technologies Used
- HTML5
- CSS3
- JavaScript (ES6+)
- Bootstrap 5
- PHP 7+
- MySQL
- Font Awesome icons

### 6. Installation
1. Place all files in your web server directory
2. Create MySQL database using `database.sql`
3. Update `config.php` with your database credentials
4. Access `index.html` in your browser
