# GrowDev - Project Management System

A secure Laravel-based project management application with robust authentication and authorization.

## 🎯 Features Overview

Based on the use case diagram, this application implements:

### Authentication Flow
1. **Welcome Page** → Entry point with "Create Profile" and "Log in" buttons
2. **Sign Up Page** → Registration with Name, Email, Password, Confirm Password
3. **Login Page** → Authentication with Email, Password, and "Forgot Password" link
4. **Dashboard** → Authenticated area with "New Project" functionality

### Security Implementation ✅
- ✅ **Password Hashing**: Bcrypt with configurable rounds
- ✅ **CSRF Protection**: Automatic token validation on all forms
- ✅ **Rate Limiting**: 5 login attempts/minute, 3 password reset/minute
- ✅ **Authorization Policies**: Users can only access their own projects
- ✅ **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- ✅ **XSS Protection**: Blade template escaping
- ✅ **Session Security**: Secure session management with regeneration
- ✅ **Password Reset**: Secure token-based flow via email

## 🚀 Quick Start

### Prerequisites
- PHP 8.4+
- Composer
- MySQL (XAMPP)
- Node.js & NPM

### Installation

1. **Start XAMPP MySQL** (Port 3306)

2. **The database and migrations are already configured!**
   ```bash
   # Database 'laravel' already created
   # Migrations already run
   ```

3. **Start the development server**
   ```bash
   php artisan serve
   ```

4. **Visit the application**
   ```
   http://127.0.0.1:8000
   ```

## 📱 Application Flow (As Per Use Case Diagram)

### 1. Welcome Page (`/`)
- Two primary actions:
  - **Create Profile** → Redirects to Sign Up page
  - **Log in** → Redirects to Login page

### 2. Sign Up Page (`/register`)
- Fields:
  - **Name** (required)
  - **Email** (required, unique)
  - **Password** (required, min 8 chars)
  - **Confirm Password** (must match)
- On success → Automatically logged in → Redirected to Dashboard

### 3. Login Page (`/login`)
- Fields:
  - **Email** (required)
  - **Password** (required)
- Features:
  - **Forgot Password?** link
  - **Remember Me** checkbox
  - Link to registration for new users
- On success → Redirected to Dashboard

### 4. Dashboard (`/dashboard`)
- **New Project** button (top right)
- List of user's projects with:
  - Project name
  - Description
  - Status badge (Active/Completed/On Hold)
  - Created timestamp
  - Edit and Delete actions

### 5. Project Management
- **Create Project**: Name, Description, Status
- **Edit Project**: Update any field
- **Delete Project**: With confirmation dialog
- **Authorization**: Users can only manage their own projects

## 🗂️ Database Schema

### Users
```sql
- id (Primary Key)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- password (HASHED)
- remember_token
- created_at, updated_at
```

### Projects
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- name (VARCHAR)
- description (TEXT, nullable)
- status (ENUM: 'active', 'completed', 'on_hold')
- created_at, updated_at
```

### Sessions
```sql
- id (VARCHAR, Primary Key)
- user_id (Foreign Key, nullable)
- ip_address (VARCHAR)
- user_agent (TEXT)
- payload (LONGTEXT)
- last_activity (INTEGER)
```

## 🔒 Security Features

For complete security documentation, see [SECURITY.md](SECURITY.md)

### Rate Limiting
- **Login**: 5 attempts per minute
- **Registration**: 5 attempts per minute  
- **Password Reset**: 3 attempts per minute
- **Email Verification**: 6 attempts per minute

### Authorization
- Project policies ensure users can only:
  - View their own projects
  - Edit their own projects
  - Delete their own projects

### Password Security
- Bcrypt hashing (12 rounds)
- Password confirmation required
- Secure password reset flow
- Token-based reset links

## 📋 Available Routes

### Public Routes
```
GET  /                   Welcome page
GET  /register          Sign up form
POST /register          Process registration
GET  /login             Login form
POST /login             Process login
GET  /forgot-password   Password reset request
POST /forgot-password   Send reset email
GET  /reset-password    Password reset form
POST /reset-password    Process password reset
```

### Authenticated Routes
```
GET    /dashboard              User dashboard
GET    /projects/create        New project form
POST   /projects               Store new project
GET    /projects/{id}/edit     Edit project form
PUT    /projects/{id}          Update project
DELETE /projects/{id}          Delete project
GET    /profile                User profile
POST   /logout                 Logout
```

## 🎨 UI Components

Built with **Tailwind CSS** for a modern, responsive interface:
- Clean welcome page with centered CTAs
- Professional form layouts
- Status badges with color coding
- Icon-based action buttons
- Toast notifications for success/error messages
- Responsive design (mobile-friendly)

## 🧪 Testing

Test the authentication flow:
```bash
# 1. Visit welcome page
http://127.0.0.1:8000

# 2. Create a new account
Click "Create Profile" → Fill form → Submit

# 3. You'll be logged in and redirected to Dashboard

# 4. Create a project
Click "New Project" → Fill form → Submit

# 5. Test logout and login
Click "Log out" → Try logging back in

# 6. Test forgot password
Click "Forgot Password?" → Enter email
```

## 🛠️ Tech Stack

- **Backend**: Laravel 12.34.0
- **PHP**: 8.4.13
- **Database**: MySQL via XAMPP
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite
- **Assets**: npm packages

## 📁 Project Structure

```
GrowDev/
├── app/
│   ├── Http/Controllers/
│   │   ├── ProjectController.php    # Project CRUD
│   │   └── Auth/                    # Auth controllers
│   ├── Models/
│   │   ├── User.php                 # User model
│   │   └── Project.php              # Project model
│   └── Policies/
│       └── ProjectPolicy.php        # Authorization
├── resources/views/
│   ├── welcome.blade.php            # Landing page
│   ├── dashboard.blade.php          # User dashboard
│   ├── auth/
│   │   ├── register.blade.php       # Sign up
│   │   ├── login.blade.php          # Login
│   │   └── forgot-password.blade.php
│   └── projects/
│       ├── create.blade.php         # New project
│       └── edit.blade.php           # Edit project
├── routes/
│   ├── web.php                      # Application routes
│   └── auth.php                     # Auth routes
└── database/migrations/             # Database schema
```

## 📝 Environment Configuration

Current setup:
```env
APP_NAME=GrowDev
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

## 🎯 Use Case Diagram Implementation Status

| Page/Feature | Status | Notes |
|-------------|--------|-------|
| Welcome Page | ✅ Complete | With "Create Profile" and "Log in" buttons |
| Sign Up Page | ✅ Complete | Name, Email, Password, Confirm Password |
| Log in Page | ✅ Complete | Email, Password, Forgot Password link |
| Dashboard | ✅ Complete | "New Project" button + project listing |
| Forgot Password | ✅ Complete | Full password reset flow |
| CSRF Protection | ✅ Complete | All forms protected |
| Rate Limiting | ✅ Complete | 5 attempts/minute on auth |
| Authorization | ✅ Complete | Policy-based access control |
| Password Hashing | ✅ Complete | Bcrypt encryption |

## 🚀 Next Steps / Enhancements

Potential improvements:
- Email verification on registration
- Two-factor authentication (2FA)
- Project sharing/collaboration
- File attachments for projects
- Activity logging/audit trail
- API endpoints with Sanctum
- Advanced project filtering/search
- Dark mode support

## 📞 Support

For issues or questions:
- Check [SECURITY.md](SECURITY.md) for security-related information
- Review Laravel documentation: https://laravel.com/docs

---

**Status**: ✅ Fully Functional - Ready for Development/Testing
**Last Updated**: October 20, 2025
