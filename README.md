# 🏆 SPK-MFEP: Sistem Pendukung Keputusan Program Keluarga Harapan

<div align="center">
  <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/AdminLTE-3.2+-007ACC?style=for-the-badge&logo=admin&logoColor=white" alt="AdminLTE">
  <img src="https://img.shields.io/badge/Bootstrap-5.3+-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</div>

<div align="center">
  <h3>🎯 Decision Support System for Family Hope Program Recipients using MFEP (Multi Factor Evaluation Process) Method</h3>
  <p><em>A comprehensive web-based application for determining PKH (Program Keluarga Harapan) beneficiaries with advanced MFEP ranking algorithms and modern UI design.</em></p>
</div>

---

## 🌟 Features Overview

### 🔧 Core Functionality
- **📊 Multi-Criteria Decision Analysis** - Advanced MFEP algorithm implementation
- **👥 Citizen Data Management** - Comprehensive family profile with integrated criteria input
- **🎯 Data Kriteria & Sub Kriteria** - Flexible weighting system for evaluation criteria
- **📈 Ranking System** - Automated ranking with MFEP formula (∑WE - WP)
- **📄 PDF Export** - Professional reports with detailed MFEP analysis
- **🔐 User Authentication** - Secure admin panel with session management
- **📋 Audit Trail** - Complete logging system for criteria weight changes

### 🎨 Modern UI/UX
- **🌙 Dark Theme Design** - Modern dark interface with high contrast
- **📱 Responsive Layout** - Mobile-first design with AdminLTE framework
- **⚡ Real-time Updates** - Dynamic content loading and validation
- **📊 Interactive Tables** - Advanced DataTables with sorting and filtering
- **🎭 Modern Components** - Custom CSS framework with smooth animations

---

## 🏗️ System Architecture

```
SPK-MFEP System
├── 🎨 Frontend Layer
│   ├── Modern AdminLTE Interface
│   ├── Custom CSS Framework
│   ├── JavaScript Enhancements
│   └── Responsive Design
├── ⚙️ Backend Layer
│   ├── PHP Core Logic
│   ├── MFEP Algorithm Engine
│   ├── PDF Generation (DOMPDF)
│   └── Session Management
├── 🗃️ Database Layer
│   ├── MySQL Database
│   ├── Citizen Records (with integrated criteria)
│   ├── Criteria Management
│   ├── Calculation Results (MFEP)
│   └── Audit Trail (tbl_log_bobot)
└── 📊 Reporting Layer
    ├── PDF Export System
    ├── MFEP Statistical Analysis
    └── Ranking Reports
```

---

## 🔬 MFEP Algorithm Implementation

The Multi Factor Evaluation Process (MFEP) method evaluates alternatives based on multiple criteria with weight evaluation and problem consideration:

### 📐 Mathematical Formula
```
1. Factor Evaluation (E):  E = X / X_max
2. Weight Evaluation (WE): WE = Weight × E
3. Total WE:               ∑WE = WE1 + WE2 + ... + WEn
4. Weight Problem (WP):    WP = Σ((1-Weight) × (1-E))
5. MFEP Score:            MFEP = ∑WE - WP
```
Where:
- `E` = Normalized factor (0-1)
- `WE` = Weighted evaluation for each criteria
- `∑WE` = Total weighted evaluation
- `WP` = Total weighted problem
- `MFEP` = Final Multi Factor Evaluation Process score

### 🎯 Evaluation Criteria (PKH Standards)
1. **👴 C1**: Number of elderly family members
2. **♿ C2**: Number of family members with severe disabilities
3. **🎒 C3**: Number of elementary school-age children
4. **📚 C4**: Number of middle school-age children
5. **🎓 C5**: Number of high school-age children
6. **👶 C6**: Number of toddlers in the family
7. **🤱 C7**: Number of pregnant mothers in the family
8. **🔧 C8**: Reserve criteria for future development

---

## 🚀 Quick Start Guide

### 📋 Prerequisites
- **XAMPP/WAMP/LAMP** - Local server environment
- **PHP 7.4+** - Server-side scripting
- **MySQL 8.0+** - Database management
- **Modern Web Browser** - Chrome, Firefox, Edge, Safari

### ⚡ Installation Steps

1. **📥 Clone Repository**
   ```bash
   git clone https://github.com/yourusername/spksaw.git
   cd spksaw
   ```

2. **🗃️ Database Setup**
   ```bash
   # Create database
   mysql -u root -p
   CREATE DATABASE spksaw;
   
   # Import database structure
   mysql -u root -p spksaw < database/spksaw.sql
   ```

3. **🔧 Configuration**
   ```php
   // Edit configurasi/koneksi.php
   $host = "localhost";
   $user = "root";
   $pass = "your_password";
   $db = "spksaw";
   ```

4. **🌐 Launch Application**
   ```bash
   # Place in htdocs/www folder
   cp -r spksaw /path/to/xampp/htdocs/
   
   # Access via browser
   http://localhost/spksaw
   ```

### 🔑 Default Login
- **Username**: `admin`
- **Password**: `admin`

---

## 📁 Project Structure

```
spksaw/
├── 📂 administrator/           # Admin panel
│   ├── 🎨 css/                # Stylesheets
│   │   ├── modern-framework.css
│   │   └── modern-content.css
│   ├── 📜 js/                 # JavaScript files
│   ├── 🖼️ images/             # UI assets
│   ├── 📊 modul/              # Core modules
│   │   ├── mod_warga/         # Citizen management
│   │   ├── mod_kriteria/      # Criteria management
│   │   └── mod_perankingan/   # Ranking system
│   └── 🔧 plugins/            # Third-party libraries
├── 📂 configurasi/            # Configuration files
│   ├── koneksi.php           # Database connection
│   ├── library.php           # Helper functions
│   └── class_paging.php      # Pagination class
├── 🗃️ database/              # Database files
│   └── spksaw.sql            # Database structure
├── 📁 foto_siswa/            # Profile images
├── 📦 vendor/                # Composer dependencies
│   └── dompdf/               # PDF generation
└── 📄 README.md              # Documentation
```

---

## 🛠️ Technology Stack

### 🖥️ Backend Technologies
| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | 7.4+ | Server-side logic |
| **MySQL** | 8.0+ | Database management |
| **DOMPDF** | 2.0+ | PDF generation |
| **Composer** | 2.0+ | Dependency management |

### 🎨 Frontend Technologies
| Technology | Version | Purpose |
|------------|---------|---------|
| **AdminLTE** | 3.2+ | Admin dashboard framework |
| **Bootstrap** | 5.3+ | CSS framework |
| **jQuery** | 3.6+ | JavaScript library |
| **DataTables** | 1.13+ | Table enhancement |
| **Font Awesome** | 6.4+ | Icon library |

### 🧩 Additional Libraries
- **Custom CSS Framework** - Modern dark theme implementation
- **Responsive Design System** - Mobile-first approach
- **Interactive Components** - Enhanced UX elements
- **Form Validation** - Client-side and server-side validation

---

## 📊 Database Schema

### 🏠 Main Tables

#### 👥 `data_warga` - Citizen Information
```sql
CREATE TABLE data_warga (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_lengkap VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    jumlah_lansia INT DEFAULT 0,
    jumlah_disabilitas_berat INT DEFAULT 0,
    jumlah_anak_sd INT DEFAULT 0,
    jumlah_anak_smp INT DEFAULT 0,
    jumlah_anak_sma INT DEFAULT 0,
    jumlah_balita INT DEFAULT 0,
    jumlah_ibu_hamil INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 🎯 `tbl_kriteria` - Evaluation Criteria
```sql
CREATE TABLE tbl_kriteria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_kriteria VARCHAR(10) NOT NULL,
    nama_kriteria VARCHAR(100) NOT NULL,
    bobot DECIMAL(3,2) NOT NULL,
    jenis ENUM('benefit', 'cost') DEFAULT 'benefit'
);
```

#### 📈 `tbl_hasil_mfep` - MFEP Calculation Results
```sql
CREATE TABLE tbl_hasil_mfep (
    id_hasil INT PRIMARY KEY AUTO_INCREMENT,
    id_warga INT NOT NULL,
    nama_warga VARCHAR(100) NOT NULL,
    -- Matriks Keputusan (X)
    C1 INT, C2 INT, C3 INT, C4 INT, C5 INT, C6 INT, C7 INT, C8 INT,
    -- Nilai Evaluasi Factor (E)
    E1 DECIMAL(5,4), E2 DECIMAL(5,4), ... E8 DECIMAL(5,4),
    -- Nilai Bobot Evaluasi (WE)
    WE1 DECIMAL(6,4), WE2 DECIMAL(6,4), ... WE8 DECIMAL(6,4),
    -- Hasil Akhir
    total_we DECIMAL(6,4),
    nilai_mfep DECIMAL(6,4),
    ranking INT NOT NULL,
    rekomendasi ENUM('Ya', 'Tidak') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_warga) REFERENCES data_warga(id_warga)
);
```

---

## 🔧 Advanced Features

### 📊 MFEP Calculation Engine
- **Factor Evaluation (E)** - Automatic normalization: E = X / X_max
- **Weight Evaluation (WE)** - Weighted calculation: WE = Weight × E
- **Total WE Calculation** - Sum of all weighted evaluations
- **Weight Problem (WP)** - Problem consideration formula
- **MFEP Score** - Final score: MFEP = ∑WE - WP
- **Ranking Algorithm** - Automated ranking with tie-breaking mechanisms
- **Real-time Updates** - Dynamic recalculation when data changes

### 📄 PDF Report System
- **Comprehensive Reports** - Detailed family information and scoring
- **Professional Layout** - Clean, printable PDF documents
- **Criteria Explanation** - Detailed breakdown of evaluation criteria
- **Statistical Summary** - Overview of results and recommendations

### 🎨 Modern UI Framework
- **Dark Theme** - Sophisticated dark color scheme
- **Responsive Design** - Optimized for all device sizes
- **Interactive Elements** - Smooth animations and transitions
- **Accessibility** - High contrast and screen reader friendly

---

## 🔒 Security Features

- **🛡️ SQL Injection Protection** - Prepared statements and input validation
- **🔐 Session Management** - Secure login system with timeout
- **✅ Input Sanitization** - Comprehensive data cleaning
- **🚫 XSS Prevention** - Output encoding and validation
- **🔑 Access Control** - Role-based permission system

---

## 📈 Performance Optimizations

- **⚡ Database Indexing** - Optimized query performance
- **🗜️ CSS/JS Minification** - Reduced file sizes
- **📱 Responsive Images** - Optimized image loading
- **🔄 Caching System** - Session-based data caching
- **📊 Pagination** - Efficient data loading for large datasets

---

## 🧪 Testing

### 🔍 Test Coverage
- **Unit Tests** - Core algorithm validation
- **Integration Tests** - Database and API testing
- **UI Tests** - Browser compatibility testing
- **Performance Tests** - Load and stress testing

### 🐛 Debugging Tools
- **Error Logging** - Comprehensive error tracking
- **Debug Mode** - Development environment features
- **SQL Query Monitor** - Database performance tracking

---

## 🚀 Deployment Guide

### 🆓 Free Hosting Deployment (InfinityFree - Recommended)

#### 🌟 Why InfinityFree?
- ✅ **PHP 7.4+ Support** - Full compatibility
- ✅ **MySQL Database** - 400MB free storage
- ✅ **5GB File Storage** - Plenty for our project
- ✅ **No Ads** - Clean professional appearance
- ✅ **SSL Certificate** - Free HTTPS
- ✅ **99%+ Uptime** - Reliable hosting

#### 📋 Step-by-Step InfinityFree Deployment

1. **🔗 Sign Up at InfinityFree**
   ```
   Visit: https://infinityfree.net
   Create free account
   ```

2. **🗃️ Create Database**
   ```
   Control Panel → MySQL Databases
   Database Name: [your_db_name]
   Username: [auto_generated]
   Password: [create_strong_password]
   ```

3. **📁 Upload Files**
   ```
   Use File Manager or FTP:
   - Upload all files to /htdocs/ folder
   - Exclude: .git, .vscode, node_modules
   ```

4. **🔧 Update Configuration**
   ```php
   // Edit configurasi/koneksi.php
   $host = "sqlXXX.infinityfree.com"; // From control panel
   $user = "if0_XXXXXXX_dbuser";      // From control panel  
   $pass = "your_database_password";   // Your password
   $db = "if0_XXXXXXX_database";      // Database name
   ```

5. **📊 Import Database**
   ```
   Control Panel → phpMyAdmin
   Import → Choose database/spksaw.sql
   Execute import
   ```

6. **🔑 Update Admin Credentials**
   ```sql
   -- Access phpMyAdmin and run:
   UPDATE tbl_admin SET 
   username = 'your_admin', 
   password = MD5('your_secure_password') 
   WHERE id = 1;
   ```

#### 🌐 Your Live URL
```
https://your-subdomain.infinityfreeapp.com
```

### 🎯 Alternative Free Hosting Options

#### 📋 Comparison Table

| Platform | PHP | MySQL | Storage | Bandwidth | Ads | SSL |
|----------|-----|-------|---------|-----------|-----|-----|
| **InfinityFree** | ✅ 7.4+ | 400MB | 5GB | Unlimited | ❌ | ✅ |
| **000WebHost** | ✅ 7.4+ | 1GB | 1GB | 10GB/month | ⚠️ Small | ✅ |
| **AwardSpace** | ✅ 7.4+ | 1GB | 1GB | 5GB/month | ❌ | ✅ |

### 🌐 Production Deployment (Paid Hosting)

1. **📋 Server Requirements**
   ```bash
   - PHP 7.4+ with extensions: mysqli, pdo, gd, mbstring
   - MySQL/MariaDB 8.0+
   - Apache/Nginx web server
   - SSL certificate (recommended)
   ```

2. **🔧 Configuration**
   ```php
   // Production settings in configurasi/koneksi.php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

3. **🗃️ Database Migration**
   ```bash
   # Backup existing data
   mysqldump -u username -p existing_db > backup.sql
   
   # Import new structure
   mysql -u username -p new_db < database/spksaw.sql
   ```

### 🔒 Security Checklist
- [ ] Change default admin credentials
- [ ] Enable HTTPS/SSL
- [ ] Configure firewall rules
- [ ] Regular security updates
- [ ] Database user permissions
- [ ] File upload restrictions

---

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### 📋 Contribution Process
1. **🍴 Fork** the repository
2. **🌿 Create** a feature branch (`git checkout -b feature/AmazingFeature`)
3. **💾 Commit** your changes (`git commit -m 'Add AmazingFeature'`)
4. **📤 Push** to the branch (`git push origin feature/AmazingFeature`)
5. **🔄 Open** a Pull Request

### 📝 Coding Standards
- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Include comprehensive comments
- Write unit tests for new features
- Maintain consistent code formatting

---

## 📋 Changelog

### Version 3.0.0 (Current - MFEP)
- ✨ **New**: MFEP (Multi Factor Evaluation Process) algorithm
- ✨ **New**: Integrated criteria input in Data Warga form
- ✨ **New**: Data Kriteria & Sub Kriteria management
- ✨ **New**: Audit trail system (tbl_log_bobot)
- ✨ **New**: Comprehensive MFEP calculation report
- ✨ **New**: 5-step calculation breakdown display
- 🔧 **Changed**: Removed separate Data Klasifikasi module
- 🔧 **Changed**: Menu structure reorganization
- 🔧 **Improved**: Database structure with MFEP tables
- 🔧 **Improved**: PDF export with MFEP details
- 🔒 **Security**: Enhanced audit trail logging

### Version 2.0.0
- ✨ **New**: Modern dark theme UI
- ✨ **New**: Enhanced PDF export with detailed family information
- ✨ **New**: Responsive design for mobile devices
- 🔧 **Improved**: SAW algorithm implementation
- 🔧 **Improved**: Database structure optimization
- 🐛 **Fixed**: Various UI/UX improvements
- 🔒 **Security**: Enhanced input validation

### Version 1.0.0
- 🎉 Initial release
- 📊 Basic SAW algorithm implementation
- 👥 Citizen data management
- 🎯 Criteria management system
- 📄 Basic PDF export functionality

---

## 🆘 Troubleshooting

### 🔧 Common Issues

#### Database Connection Error
```php
// Check configurasi/koneksi.php settings
// Verify MySQL service is running
// Confirm database credentials
```

#### PDF Export Not Working
```bash
# Check DOMPDF installation
composer install

# Verify file permissions
chmod 755 vendor/dompdf/
```

#### Login Issues
```sql
-- Reset admin password
UPDATE tbl_admin SET password = MD5('admin') WHERE username = 'admin';
```

---

## 📞 Support & Contact

### 🤝 Getting Help
- **📚 Documentation**: Check this README and inline comments
- **🐛 Issues**: Report bugs via GitHub Issues
- **💬 Discussions**: Join our community discussions
- **📧 Email**: [your-email@domain.com]

### 🔗 Links
- **🌐 Live Demo**: [https://your-subdomain.infinityfreeapp.com](https://your-subdomain.infinityfreeapp.com)
- **📖 Documentation**: [GitHub Repository README](https://github.com/yourusername/spksaw)
- **💻 Source Code**: [GitHub Repository](https://github.com/yourusername/spksaw)
- **🆓 Free Hosting**: [InfinityFree](https://infinityfree.net)

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

### 📋 License Summary
- ✅ Commercial use allowed
- ✅ Modification allowed
- ✅ Distribution allowed
- ✅ Private use allowed
- ❌ Liability and warranty excluded

---

## 🙏 Acknowledgments

### 🏆 Special Thanks
- **AdminLTE Team** - For the excellent admin dashboard framework
- **DOMPDF Developers** - For the robust PDF generation library
- **Bootstrap Team** - For the responsive CSS framework
- **Open Source Community** - For continuous inspiration and support

### 📚 References
- Simple Additive Weighting (SAW) Method Research Papers
- Decision Support System Design Patterns
- Modern Web Development Best Practices
- PKH Program Government Guidelines

---

<div align="center">
  <h3>🌟 If this project helps you, please give it a star! ⭐</h3>
  <p>Made with ❤️ for better decision-making in social programs</p>
  
  <img src="https://img.shields.io/github/stars/yourusername/spksaw?style=social" alt="GitHub stars">
  <img src="https://img.shields.io/github/forks/yourusername/spksaw?style=social" alt="GitHub forks">
  <img src="https://img.shields.io/github/watchers/yourusername/spksaw?style=social" alt="GitHub watchers">
</div>

---

<div align="center">
  <sub>📅 Last updated: October 2025 | 🔄 Version 3.0.0 (MFEP)</sub>
</div>
