# GrowDev - Professional CV Management & Project Development System

A comprehensive Laravel application combining TOTP-based authentication, professional CV management, and an intelligent software development process guidance system for teams and solo developers.

## 📖 Short Description

GrowDev is an all-in-one Software as a Service (SaaS) platform designed to guide developers through the complete software development lifecycle. It empowers both individual developers and teams by providing role-based task allocation, documentation templates, email notifications, real-time collaboration features, and a shared database accessible across devices. Built with Laravel, it emphasizes security, usability, and scalability.

## 👥 Team & Task Distribution

### Team Members

| Name | Role | Expertise |
|---|---|---|
| Shoaib Ibna Omar | Full Stack Developer | Backend & Frontend |
| Mansura Yeasmin | Full Stack Developer | Frontend & Database |
| Ismail Hossain | Full Stack Developer | Backend & API |
| Sejanul Islam | Full Stack Developer | Frontend & UI/UX |
| Shafin Foysal | Full Stack Developer | Testing & DevOps |

### Task Distribution Table

| Team Member | Assigned Tasks |
|---|---|
| **Shoaib Ibna Omar** | FR1.1, FR1.2, FR1.3, FR1.4, FR1.5 (User Management), NFR4, NFR5, NFR7 (Security & Access Control) |
| **Mansura Yeasmin** | FR5.1, FR5.2, FR5.3, FR5.4, FR5.5, FR5.6 (Documentation), FR7.1, FR7.2, FR7.3, FR7.4 (Data Sharing), NFR9 (Backups) |
| **Ismail Hossain** | FR2.1, FR2.2, FR2.3, FR2.4, FR2.5, FR2.6 (Project Management), FR3.1, FR3.2, FR3.3, FR3.4, FR3.5 (Team Management), NFR1, NFR2 (Performance) |
| **Sejanul Islam** | FR4.1, FR4.2, FR4.3, FR4.4, FR4.5, FR4.6 (Task Allocation), NFR11, NFR12, NFR14 (UI/UX & Compatibility) |
| **Shafin Foysal** | FR6.1, FR6.2, FR6.3, FR6.4, FR6.5 (Email Notifications), NFR3, NFR8, NFR10, NFR15, NFR16 (Performance, Availability, DevOps) |

## 🌟 Feature List

### 🔐 Authentication & Security
- **TOTP-based Authentication**: Two-factor authentication using Google Authenticator
- **Direct TOTP Password Reset**: Reset passwords without email verification
- **Rate Limiting**: 3 attempts per minute on TOTP verification
- **5-minute Session Timeout**: For enhanced security
- **Role-Based Access Control**: User permissions based on roles
- **Secure Data Encryption**: Sensitive data encrypted at rest and in transit

### 📄 CV Management
- **Professional CV Editing**: Comprehensive CV builder with multiple sections
- **Work Experience**: Track job positions with dates and descriptions
- **Education**: Manage educational background and credentials
- **Skills**: Add skills with proficiency levels (Beginner/Intermediate/Advanced/Expert)
- **Certifications**: Store certifications with issue/expiry dates and credentials
- **PDF Export**: Download CV as professionally formatted PDF

### 🚀 Project Management
- **Project Creation**: Create solo and team-based projects
- **Project Scope Definition**: Set project objectives and scope
- **Timeline & Milestones**: Define project timelines and track milestones
- **Progress Tracking**: Monitor progress through development phases (Requirements, Design, Implementation, Testing, Deployment, Maintenance)
- **Project Dashboard**: View comprehensive status summaries

### 👥 Team Collaboration
- **Team Creation & Management**: Create and manage development teams
- **Email Invitations**: Invite team members via email
- **Custom Roles**: Define custom roles for team members
- **Role Assignment**: Assign specific roles to team members
- **Invitation Management**: Accept or decline team invitations

### 📌 Task Allocation & Tracking
- **Task Creation**: Create development aspect tasks (Backend, Frontend, UI/UX, Database, Testing, Documentation)
- **Multi-Task Assignment**: Assign multiple aspects to the same team member
- **Task Status Updates**: Team members update task progress
- **Visual Task Representation**: Graphical visualization of task assignments
- **Task Dashboard**: View all assigned tasks in one place

### 📋 Documentation & Templates
- **Pre-built Templates**: SRS, SDD, Test Plans, User Manuals, Meeting Notes, Project Charters
- **Template Customization**: Modify templates to suit project needs
- **Document Creation**: Generate documents from templates
- **Real-time Collaboration**: Collaborate on documents simultaneously
- **Multi-format Export**: Export documents as PDF, DOCX, etc.
- **Version History**: Maintain complete version history of all documents

### 📧 Email Notifications
- **Task Assignment Notifications**: Automated emails when tasks are assigned
- **Deadline Reminders**: Remind team members of upcoming deadlines
- **Status Update Notifications**: Alert team about project status changes
- **Meeting Invitations**: Send meeting invites via email
- **Customizable Preferences**: Team members control notification settings
- **Email Templates**: Pre-designed templates for different scenarios

### 🔄 Data Sharing & Synchronization
- **Shared Database**: Centralized data accessible to all team members
- **Real-time Synchronization**: Data updates instantly across all devices
- **Offline Access**: Work offline with synchronization when online
- **Access Logging**: Maintain audit trail of all data access

### 🖥️ User Interface
- **Responsive Design**: Built with Tailwind CSS
- **Live Preview**: See changes in real-time as you edit
- **Dynamic Form Sections**: Add/remove sections easily
- **Modern UI Components**: Clean, intuitive interface
- **Mobile Responsive**: Fully functional on mobile devices
- **WCAG 2.1 Compliant**: Accessible to users with disabilities

## 💻 Installation Guide

### Prerequisites
- PHP 8.4+
- MySQL 8.0+ or SQLite
- Composer
- Node.js & NPM 18+

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
- Run all migrations and seed the documentation templates
- Clear cached configuration, view, and route files

### 🚀 Quick Start (macOS/Linux)
From the project root, run the following one-liner:

```bash
composer install && npm install && php artisan migrate --seed && npm run build
```

### 🤝 Collaborator Setup Checklist
- Run `setup.bat` on Windows machines or `composer install && npm install && php artisan migrate --seed && npm run build` on macOS/Linux
- If you prefer MySQL over SQLite, update `.env` database credentials and rerun `php artisan migrate:fresh --seed`

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
   php artisan db:seed --class=DocumentationTemplateSeeder
   ```

5. **Build frontend assets**
   ```bash
   npm run build
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

   In a separate terminal, start the Vite dev server (optional, for hot reload):
   ```bash
   npm run dev
   ```

Access the application at `http://127.0.0.1:8000`

## 📊 Output Section

### Application Outputs & Deliverables

#### 1. **Web Dashboard**
   - Project overview and navigation hub
   - Real-time project status updates
   - Team member activity feed
   - Task progress visualizations

#### 2. **CV Management Interface**
   - Professional CV editor with live preview
   - PDF export functionality
   - Multiple CV templates
   - Version history tracking

#### 3. **Project Documentation**
   - Generated SRS documents
   - Software Design Documents (SDD)
   - Test Plans and Reports
   - Meeting Minutes and Action Items
   - PDF/DOCX exports

#### 4. **Task Management System**
   - Task assignment visualizations
   - Team member workload overview
   - Task status tracking dashboard
   - Progress reports

#### 5. **Email Communications**
   - Task assignment notifications
   - Deadline reminders
   - Status update notifications
   - Meeting invitations

#### 6. **Shared Database Reports**
   - Project data exports
   - Team collaboration metrics
   - Audit logs
   - Version history reports

#### 7. **Default Test Account**
   - **Email**: test@example.com
   - **Password**: password
   - Pre-configured with sample project data and team setup

## 🙏 Acknowledgments

### Technology Stack
- **Laravel Framework**: 12.34.0 - Web application framework
- **PHP**: 8.4.13 - Server-side programming language
- **MySQL/SQLite**: Database management systems
- **Tailwind CSS**: Utility-first CSS framework
- **Blade Templates**: Laravel templating engine
- **Vite**: Frontend build tool and module bundler

### Libraries & Packages
- **barryvdh/laravel-dompdf**: PDF generation
- **pragmarx/google2fa**: TOTP two-factor authentication
- **Laravel Socialite**: OAuth authentication (for future integrations)

### Contributors & Inspiration
This project was built by a dedicated team of full-stack developers with a focus on creating a comprehensive solution for software development process guidance and team collaboration.

### References
- IEEE Std 830-1998 - IEEE Recommended Practice for Software Requirements Specifications
- WCAG 2.1 Web Content Accessibility Guidelines
- RESTful API Best Practices
- Laravel Documentation: https://laravel.com/docs
- GitHub Repository: https://github.com/Shoaibvai/growdev

### Open Source Community
We acknowledge the open-source community for providing excellent tools and libraries that made this project possible.

---

## 🌟 Features (Legacy)

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
