
# GrowDev - Project Management & CV Platform

GrowDev is a collaborative project management and professional CV platform built with Laravel. It guides users and teams through the software development lifecycle, offering role-based task allocation, documentation templates, notifications, and a modern CV builder.

## 👥 Team & Task Distribution
**Developers:**
- Shoaib Ibna Omar
- Mansura Yeasmin
- Ismail Hossain
- Sejanul Islam
- Shafin Foysal
All are Full Stack Developers.

**Task Distribution:**
- Shoaib Ibna Omar: User & Team Management, Security
- Mansura Yeasmin: Project Management, Performance
- Ismail Hossain: Task Allocation, Compatibility
- Sejanul Islam: Data Sync, API, Backup
- Shafin Foysal: UI/UX, Testing, Docs

## 🌟 Feature List
- TOTP-based authentication (2FA)
- Professional CV builder & PDF export
- Role-based team & task management
- Project lifecycle guidance
- Real-time collaboration & notifications
- Documentation templates (SRS-focused; SDD retired)
- Responsive UI (Tailwind CSS)
- Secure, reliable, and accessible

> **Note:** The legacy Software Design Document (SDD) workflows were fully removed in November 2025. GrowDev now concentrates on SRS authoring and related project guidance.

## ⚡ Installation Guide
**Windows:**
Run `setup.bat` from the project root.

**macOS/Linux:**
Run:
```bash
composer install && npm install && php artisan migrate --seed
```

**Steps:**
1. Clone the repo
2. Install dependencies
3. Configure `.env` and generate app key
4. Run migrations & seeders
5. Build assets (`npm run build`)
6. Start server (`php artisan serve`)

## 📦 Output Section
- Access the app at [http://127.0.0.1:8000](http://127.0.0.1:8000)
- Features: CV builder, team/project dashboard, documentation, notifications
- PDF export for CVs
- Real-time updates for team tasks

## 🙏 Acknowledgment
Special thanks to all contributors, the Laravel community, and open-source libraries used in GrowDev.

## 🌟 Features

### 🔐 Authentication
- **TOTP-based Authentication**: Two-factor authentication using Google Authenticator
- **Direct TOTP Password Reset**: Reset passwords without email verification
- **Rate Limiting**: 3 attempts per minute on TOTP verification
- **5-minute Session Timeout**: For enhanced security

### 📄 CV Management
- **Professional CV Editing**: Comprehensive CV builder with multiple sections
- **Work Experience**: Track job positions with dates and descriptions
- **Education**: Manage educational background and credentials
- **Skills**: Add skills with proficiency levels (Beginner/Intermediate/Advanced/Expert)
- **Certifications**: Store certifications with issue/expiry dates and credentials
- **PDF Export**: Download CV as professionally formatted PDF

### � User Interface
- **Responsive Design**: Built with Tailwind CSS
- **Live Preview**: See changes in real-time as you edit
- **Dynamic Form Sections**: Add/remove work experience, education, skills, certifications
- **Modern UI Components**: Clean, intuitive interface

## 🛠️ Tech Stack

- **Laravel**: 12.34.0
- **PHP**: 8.4.13
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Blade Templates
- **PDF Generation**: barryvdh/laravel-dompdf
- **Authentication**: TOTP (pragmarx/google2fa)

## � Installation

### Prerequisites
- PHP 8.4+
- MySQL 8.0+
- Composer
- Node.js & NPM (optional, for frontend build)

### 🚀 Quick Start (Windows)
If you're on Windows, run the automated setup script from the project root:

```bash
setup.bat
```

The script will:
- Copy `.env.example` to `.env` (if it does not already exist)
- Install PHP (Composer) and Node dependencies
- Generate the application key
- Create the default SQLite database (`database/database.sqlite`)
- Run all migrations and seed the IEEE documentation templates
- Clear cached configuration, view, and route files

### 🚀 Quick Start (macOS/Linux)
From the project root, run the following one-liner:

```bash
composer install && npm install && php artisan migrate --seed
```

### 🤝 Collaborator Checklist
- Run `setup.bat` on Windows machines or `composer install && npm install && php artisan migrate --seed` on macOS/Linux.
- If you prefer MySQL over the bundled SQLite database, update the connection details in `.env` and rerun `php artisan migrate:fresh --seed`.

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd GrowDev
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=TestUserSeeder
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

Access the application at `http://127.0.0.1:8000`

## 👤 Default Test Account

- **Email**: test@example.com
- **Password**: password

## 📖 Usage

### Register a New Account

1. Go to `/register`
2. Enter your details
3. Set up TOTP (scan QR code with Google Authenticator)
4. Complete registration

### Edit Your CV

1. Log in to the application
2. Navigate to `/profile`
3. Fill in your personal information
4. Add work experience, education, skills, and certifications
5. Click **Save Changes** to persist your data

### Export CV as PDF

1. Go to `/profile`
2. Click the **Export PDF** button (top right)
3. Your CV will download as `CV_[YourName]_[Date].pdf`

### Password Reset (TOTP-based)

1. Go to `/forgot-password`
2. Enter your email
3. Enter your TOTP code from Google Authenticator
4. Set your new password
5. You'll be logged in automatically

## 📊 Database Schema

### Users Table
- Basic user information (name, email, password)
- TOTP secret for 2FA
- CV fields: phone_number, professional_summary, location, website, linkedin_url, github_url

### CV Tables
- **work_experiences**: Job positions with dates
- **educations**: Educational background
- **skills**: Skills with proficiency levels
- **certifications**: Certifications and credentials

All CV tables include cascading delete for data integrity.

## 📁 File Structure

```
GrowDev/
├── app/
│   ├── Http/Controllers/
│   │   └── ProfileController.php      # CV management
│   └── Models/
│       ├── User.php
│       ├── WorkExperience.php
│       ├── Education.php
│       ├── Skill.php
│       └── Certification.php
├── database/
│   ├── migrations/                    # Database schema
│   └── seeders/
│       └── TestUserSeeder.php         # Test data
├── resources/views/
│   ├── profile/
│   │   ├── edit.blade.php            # CV editor
│   │   └── partials/                 # Form components
│   └── cv/
│       └── pdf.blade.php             # PDF template
├── routes/
│   └── web.php                        # Application routes
└── README.md                          # This file
```

## �️ Routes

### Authentication
- `GET /register` - Registration form
- `POST /register` - Create account
- `GET /register/totp-setup` - TOTP setup page
- `POST /register/totp-setup` - Verify TOTP setup

### Profile & CV
- `GET /profile` - CV editor
- `PUT /profile` - Save CV changes
- `GET /profile/cv-pdf` - Download CV as PDF

### Dashboard
- `GET /dashboard` - User dashboard
- `GET /` - Welcome page

## ✅ API Validation Rules

### Profile Update (PUT /profile)

**Personal Information**
- `name`: required, string, max 255
- `email`: required, email, unique (per user)
- `phone_number`: nullable, string, max 20
- `professional_summary`: nullable, string, max 1000
- `location`: nullable, string, max 255
- `website`: nullable, URL
- `linkedin_url`: nullable, URL
- `github_url`: nullable, URL

**Work Experience**
- `job_title`: required, string, max 255
- `company_name`: required, string, max 255
- `description`: nullable, string
- `start_date`: required, date
- `end_date`: nullable, date
- `currently_working`: boolean

**Education**
- `school_name`: required, string, max 255
- `degree`: required, string, max 255
- `field_of_study`: required, string, max 255
- `description`: nullable, string
- `start_date`: required, date
- `end_date`: required, date

**Skills**
- `skill_name`: required, string, max 255
- `proficiency`: required, enum (beginner, intermediate, advanced, expert)

**Certifications**
- `certification_name`: required, string, max 255
- `issuer`: required, string, max 255
- `description`: nullable, string
- `issue_date`: required, date
- `expiry_date`: nullable, date
- `credential_url`: nullable, URL

## 🔒 Security Considerations

- All CV data is private and user-specific
- TOTP-based 2FA prevents unauthorized access
- Password reset requires TOTP verification
- Email validation prevents fake accounts
- Database transactions ensure data consistency
- XSS prevention through Blade templating
- CSRF token validation on all forms

## 🚀 Development

### Clear Cache
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Run Tests
```bash
php artisan test
```

### Database Refresh
```bash
php artisan migrate:refresh --seed
```

## 🐛 Troubleshooting

### TOTP Setup Issues
- Ensure your device clock is synchronized
- Try adding the account manually if QR code scan fails
- Use the backup codes for emergency access

### PDF Export Not Working
- Ensure the PDF library is installed: `composer require barryvdh/laravel-dompdf`
- Check write permissions on storage directory

### Database Connection Issues
- Verify MySQL is running
- Check `.env` database credentials
- Run migrations: `php artisan migrate`

## 📝 License

This project is open source and available under the MIT license.
