# 🎻 Sonata Violin Course Management System

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EF4223?style=flat-square&logo=codeigniter)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat-square&logo=tailwind-css)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql)

A comprehensive music course management system specifically designed for violin courses, handling student enrollment, class scheduling, and progress reporting efficiently and seamlessly.

[Features](#-key-features) • [Tech Stack](#️-tech-stack) • [Installation](#-installation) • [User Roles](#-user-roles--access-control)

</div>

---

## 📋 Table of Contents

- [About The Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#️-tech-stack)
- [System Architecture](#-system-architecture)
- [User Roles & Access Control](#-user-roles--access-control)
- [Installation](#-installation)
- [Configuration](#️-configuration)
- [Usage](#-usage)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## 🎯 About The Project

**Sonata Violin** is a web-based music course management system built specifically to manage violin course operations comprehensively. This system combines ease of self-registration for prospective students with a powerful management dashboard for course administrators.

Built with **Clean Code** principles and adopting **Role-Based Access Control (RBAC)** for maximum security, Sonata Violin provides an end-to-end solution from registration, payment, scheduling, to student progress tracking.

---

## 🚀 Key Features

### 1. 🌐 Self-Registration (Public SPA Page)

Prospective students can register **without login** through a user-friendly Single Page Application (SPA):

- ✅ **Interactive Registration Form** - Intuitive and easy-to-use interface
- 💳 **Course Package System** - Transparent package options with clear pricing and duration
- 💰 **Pay-at-Front System** - One-time upfront payment, no additional fees
- 📤 **Payment Proof Upload** - Mandatory upload of payment proof at the end of registration form
- 🔔 **Real-time Notifications** - Registration status sent immediately

### 2. 📊 Master Data Management

Complete and structured master data management system:

#### Instructor Master
- Professional instructor database
- Track specialization and instructor availability
- Teaching history and class records

#### Room Master
- Classroom availability management
- Room capacity and facilities
- Real-time usage status

#### Student Master
- Active student and alumni database
- Complete profile and emergency contacts
- Course history and achievements

#### Package Master
- Course package variations setup
- Flexible pricing and duration
- Detailed benefit descriptions for each package

### 3. 🔄 Transaction & Operational Modules

Integrated and efficient operational system:

#### 💵 Registration
- Comprehensive registration form
- Automatic data validation
- Queue system for processing

#### ✅ Payment Verification
- Verification dashboard for admin/operator
- Payment proof preview
- Approve/reject with reasons
- Automatic notifications to students

#### 📅 Smart Scheduling (Class Schedule)
- **Conflict Prevention System** - Automatic conflict detection system
- Validation of instructor, room, and time availability
- System automatically rejects if time, day, and date are already in use
- Visual calendar for easy scheduling
- Filter by instructor/room/student

#### 📝 Attendance
- Attendance recording per session
- Status: Present, Excused, Sick, Absent
- Additional notes for each session
- Student attendance statistics

#### 📈 Course Progress
- Student skill development tracking
- Evaluation per session
- Milestones and achievements
- Visual progress charts

#### 📊 Reports
- Registration and payment recap
- Student attendance reports
- Revenue analysis
- Export to PDF/Excel
- Analytics dashboard

---

## 🛠️ Tech Stack

### Backend
- **PHP 8.1+** - Server-side scripting
- **CodeIgniter 4** - PHP Framework with MVC architecture
- **MySQL** - Relational Database Management System

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **JavaScript (Vanilla)** - Interactivity and dynamic content
- **SPA Architecture** - Single Page Application for public pages

### Development Tools
- **Composer** - PHP dependency management
- **NPM** - Node package management
- **Git** - Version control system

---

## 🏗️ System Architecture

```
sonata-violin/
├── app/
│   ├── Config/         # Application configuration
│   ├── Controllers/    # Business logic handlers
│   ├── Models/         # Database interaction layer
│   ├── Views/          # Presentation layer
│   ├── Filters/        # Middleware (Auth, RBAC)
│   ├── Helpers/        # Utility functions
│   └── Libraries/      # Custom libraries
├── public/
│   ├── assets/         # CSS, JS, Images
│   └── uploads/        # User uploaded files
├── writable/
│   ├── logs/           # Application logs
│   └── cache/          # Cache files
├── tests/              # Unit & Integration tests
└── vendor/             # Dependencies
```

### MVC Pattern Implementation

**Model** → Handles data logic and database
**View** → Displays interface to user
**Controller** → Connects Model and View, manages application flow

---

## 🔐 User Roles & Access Control

The system implements **Role-Based Access Control (RBAC)** with 3 access levels:

### 👑 Admin (Full Access)
**Access Rights:**
- ✅ Full CRUD on all modules
- ✅ User management (add/edit/delete Operator & Instructor)
- ✅ Access to all reports and analytics
- ✅ System configuration
- ✅ Payment verification
- ✅ Global schedule management

**Accessible Menus:**
- Dashboard
- Master Data (Instructor, Room, Student, Package)
- Registration
- Payment Verification
- Class Schedule
- Attendance
- Course Progress
- Reports
- User Management

---

### 🔧 Operator (Transaction Access Only)
**Access Rights:**
- ✅ Registration payment verification
- ✅ Class schedule management
- ✅ Open class attendance
- ❌ Cannot add/edit/delete master data
- ❌ Cannot manage users

**Accessible Menus:**
- Dashboard (limited)
- Registration (cannot edit data arbitrarily)
- Payment Verification
- Class Schedule
- Attendance

**Strict Restrictions:**
- Cannot access user management page
- Cannot modify master data
- Can only view limited reports

---

### 🎓 Instructor (Limited Access)
**Access Rights:**
- ✅ View **own teaching schedule** only
- ✅ Input attendance for **own classes**
- ✅ Input progress for **students in own classes**
- ❌ Cannot see other instructors' classes
- ❌ Cannot access other modules

**Accessible Menus (Only 3 Pages):**
1. **Class Schedule** - Filtered to show only assigned classes
2. **Attendance** - Input attendance for own classes only
3. **Course Progress** - Evaluate students in own classes only

**Automatic Filtering System:**
- All displayed data is automatically filtered by instructor ID
- Instructors cannot access or view other classes' data
- Protection at database query level for maximum security

---

## 💻 Installation

### Prerequisites

Ensure your system has:
- PHP >= 8.1+
- MySQL >= 5.7
- Composer
- Node.js & NPM (if you want to use Tailwind locally)
- Git

### Installation Steps

1. **Clone Repository**
   ```bash
   git clone https://github.com/Codenames-Ren/sonata-violin.git
   ```

2. **Navigate to Project Directory**
   ```bash
   cd sonata-violin
   ```

3. **Install PHP Dependencies**
   ```bash
   composer install
   ```

4. **Install Frontend Dependencies**
   ```bash
   npm install
   npm run build
   ```

5. **Configure Environment**
   ```bash
   # Copy env file to .env
   cp env .env
   
   # Or on Windows
   copy env .env
   ```

6. **Edit .env File**
   
   Open `.env` file and adjust database configuration:
   ```env
   database.default.hostname = localhost
   database.default.database = sonata_violin_db
   database.default.username = your_mysql_username
   database.default.password = your_mysql_password
   database.default.DBDriver = MySQLi
   ```

7. **Create Database**
   ```sql
   CREATE DATABASE sonata_violin_db;
   ```

8. **Run Migration**
   ```bash
   php spark migrate
   ```

9. **Run Seeder**
   ```bash
   php spark db:seed UserSeeder
   ```

10. **Generate App Key (Optional)**
    ```bash
    php spark key:generate
    ```

11. **Start Development Server**
    ```bash
    php spark serve
    ```

12. **Access Application**
    
    Open your browser and go to: `http://localhost:8080`

---

## ⚙️ Configuration

### Default Login Credentials

After running the seeder, use the following credentials:

**Admin:**
```
Username: admin
Password: admin123
```

**Operator:**
```
Username: operator
Password: operator123
```

**Instructor:**
```
Username: instruktur
Password: instruktur123
```

> ⚠️ **Important:** Change default passwords immediately after first login!

### Upload Configuration

Edit `app/Config/App.php` file to configure:
- Maximum file upload size
- Allowed file types for payment proof
- Upload directory path

### Email Configuration (Optional)

For email notification features, edit `app/Config/Email.php` or input in .env:
```php
public $SMTPHost = 'your_smtp_host';
public $SMTPUser = 'your_email@domain.com';
public $SMTPPass = 'your_password';
```

---

## 📖 Usage

### For Prospective Students

1. Access public registration page
2. Fill out complete registration form
3. Select desired course package
4. Upload payment transfer proof
5. Notification will be sent to Email (if email config is set)
6. Submit and wait for admin verification

### For Admin

1. Login to admin dashboard
2. Manage master data (instructor, room, package)
3. Verify new student payments
4. Arrange class schedules with smart scheduling
5. Monitor progress and generate reports

### For Operator

1. Login to operator dashboard
2. Verify registration payments
3. Arrange class schedules
4. Input student attendance

### For Instructor

1. Login to instructor dashboard
2. Check teaching schedule
3. Input student attendance
4. Update student learning progress

---

## 🔒 Security

### Security Implementation

- ✅ **RBAC (Role-Based Access Control)** - Access restrictions based on roles
- ✅ **CSRF Protection** - Token validation on every form
- ✅ **XSS Prevention** - Input sanitization and output escaping
- ✅ **SQL Injection Prevention** - Prepared statements and query builder
- ✅ **Password Hashing** - Using bcrypt for password encryption
- ✅ **Session Security** - Secure session handling with regeneration
- ✅ **File Upload Validation** - Strict validation for file uploads
- ✅ **Authentication Middleware** - Filter for route protection

### Best Practices

- Always use HTTPS in production
- Change all default credentials
- Regular database backups
- Update dependencies regularly
- Monitor application logs
- Implement rate limiting for API endpoints

---

## 🎨 Highlighted Features

### Conflict Prevention System

Scheduling system with automatic conflict validation:
- ✅ Instructor conflict detection (cannot teach 2 classes simultaneously!)
- ✅ Room conflict detection (one room at a time)
- ✅ Time, day, and date validation
- ✅ Visual alert if conflict occurs

### Smart Dashboard

- 📊 Real-time statistics
- 📈 Revenue and registration charts
- 📅 Upcoming schedule
- ⚠️ Automatic alerts and reminders

### Responsive Design

- 📱 Mobile-friendly interface
- 💻 Optimized for desktop
- 🎨 Modern UI with Tailwind CSS
- ⚡ Fast loading with lazy loading

---

## 🤝 Contributing

Contributions are always welcome! If you'd like to contribute:

1. Fork this repository
2. Create a new feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Create a Pull Request

### Guidelines

- Follow CodeIgniter 4 coding standards
- Write clean and readable code
- Add comments for complex logic
- Test features before submitting PR
- Update documentation if necessary

---

## 📝 License

This project is licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Bayu Sukma**

- GitHub: [@Codenames-Ren](https://github.com/Codenames-Ren)
- Project Link: [https://github.com/Codenames-Ren/sonata-violin](https://github.com/Codenames-Ren/sonata-violin)

---

## 🙏 Acknowledgments

- [CodeIgniter Team](https://codeigniter.com/) for the awesome PHP framework
- [Tailwind CSS](https://tailwindcss.com/) for the utility-first CSS framework
- [SweetAlert2](https://sweetalert2.github.io/) for the beautiful, responsive, and customizable popup boxes
- All contributors who have helped this project

---

<div align="center">

**© 2025 Sonata Violin Project. All Rights Reserved.**

⭐ If you find this project helpful, don't forget to give it a star!

[Report Bug](https://github.com/Codenames-Ren/sonata-violin/issues) • [Request Feature](https://github.com/Codenames-Ren/sonata-violin/issues)

</div>
