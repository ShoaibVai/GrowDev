# GrowDev - Project Management Platform

<div align="center">

![GrowDev](Logo/logo.png)

**A comprehensive project management platform for software development teams**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=flat-square&logo=vite)](https://vitejs.dev)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Structure](#-project-structure)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [Team Contribution](#-team-contribution)
- [License](#-license)

---

## 🌟 Overview

GrowDev is a modern, full-stack project management platform designed specifically for software development teams. It combines traditional project management features with AI-powered task generation, comprehensive requirement tracking, and team collaboration tools.

### Key Highlights

- 🤖 **AI-Powered Task Generation** using Google Gemini API
- 📊 **Kanban Board** for visual project tracking
- 📝 **SRS Documentation** with functional & non-functional requirements
- 👥 **Team Management** with role-based access control
- 📈 **Progress Tracking** with real-time updates
- 🔔 **Smart Notifications** with digest options
- 📱 **Responsive Design** works on all devices

---

## ✨ Features

### Project Management
- Create and manage multiple projects
- Kanban board with drag-and-drop functionality
- Task dependencies and relationships
- Priority and status tracking
- Time estimation and tracking

### AI Task Generation
- Automatic task creation from requirements
- Intelligent role assignment
- Workload balancing across team
- Smart dependency detection
- See: [AI Features Documentation](docs/features/GEMINI_INTEGRATION.md)

### Requirements Management
- Software Requirements Specification (SRS) documents
- Functional requirements tracking
- Non-functional requirements with metrics
- Acceptance criteria management
- Traceability between requirements and tasks

### Team Collaboration
- Team creation and management
- Role-based permissions (10+ system roles)
- Team member invitations
- Activity tracking and history
- Real-time notifications

### Documentation
- Project documentation with templates
- Diagram support (UML, ERD, etc.)
- Markdown editor
- Version history

### User Profiles
- Professional profile with skills
- Education and certifications
- Work experience tracking
- Project portfolio

---

## 🛠 Tech Stack

### Backend
- **Laravel 11.x** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Queue** - Background Jobs
- **Laravel Notifications** - Email & Digest System

### Frontend
- **Blade Templates** - Server-side Rendering
- **Tailwind CSS 3.x** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript Framework
- **Vite 7.x** - Modern Build Tool
- **Axios** - HTTP Client

### AI Integration
- **Google Gemini API** - AI Task Generation (Gemini Flash Latest)
- **Direct API Integration** - Frontend AI calls

### Development Tools
- **PHPUnit** - Testing Framework
- **Laravel Pint** - Code Style
- **NPM** - Package Management
- **Git** - Version Control

---

## 📁 Project Structure

```
GrowDev/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Events/            # Event classes
│   ├── Http/
│   │   ├── Controllers/   # Request handlers
│   │   ├── Middleware/    # HTTP middleware
│   │   └── Requests/      # Form requests
│   ├── Models/            # Eloquent models
│   ├── Notifications/     # Notification classes
│   ├── Policies/          # Authorization policies
│   ├── Services/          # Business logic
│   │   └── AI/            # AI-related services
│   └── Providers/         # Service providers
├── bootstrap/             # Framework bootstrap
├── config/                # Configuration files
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories
├── docs/                  # 📚 Documentation
│   ├── api/               # API documentation
│   ├── features/          # Feature guides
│   └── setup/             # Setup instructions
├── public/                # Public assets
│   └── build/             # Compiled assets
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/
│   │   ├── modules/       # Feature modules
│   │   ├── services/      # JS services (AI, etc.)
│   │   ├── utils/         # Utility functions
│   │   └── config/        # JS configuration
│   └── views/             # Blade templates
├── routes/                # Application routes
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   ├── auth.php           # Auth routes
│   └── channels.php       # Broadcast channels
├── scripts/               # Utility scripts
├── storage/               # File storage
├── tests/                 # Automated tests
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
└── vendor/                # Composer dependencies
```

---

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 8.0+
- Git

### Quick Start

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

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Configure database in .env
   php artisan migrate:fresh --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   ```
   http://localhost:8000
   ```

### Platform-Specific Setup

#### Windows
```bash
setup.bat
```

#### Linux/Mac
```bash
chmod +x setup.sh
./setup.sh
```

See [docs/setup/](docs/setup/) for detailed installation instructions.

---

## ⚙️ Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME=GrowDev
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growdev
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

# AI Configuration
GEMINI_API_KEY=your-api-key-here
GEMINI_PROJECT=growdev
GEMINI_PROJECT_NAME=projects/your-project-id
GEMINI_PROJECT_NUMBER=your-project-number

# Queue Configuration
QUEUE_CONNECTION=database
```

### Mail Setup

For production, configure a real mail service:
- **Resend**: Set `RESEND_KEY`
- **Postmark**: Set `POSTMARK_TOKEN`
- **SMTP**: Configure SMTP settings

### Queue Workers

Run queue workers for background jobs:
```bash
php artisan queue:work
```

---

## 📖 Usage

### Default Credentials

After seeding, use these credentials:

**Admin Account:**
- Email: `admin@growdev.com`
- Password: `password`

**Demo Users:**
- See [docs/setup/SEED_DATA.txt](docs/setup/SEED_DATA.txt) for all demo accounts

### Creating Your First Project

1. **Register/Login** to the platform
2. **Create a Project** from the dashboard
3. **Add Team Members** (for team projects)
4. **Create SRS Document** with requirements
5. **Generate Tasks** using AI or manually
6. **Assign Tasks** to team members
7. **Track Progress** on the Kanban board

### Using AI Task Generation

1. Navigate to your project
2. Click "🤖 AI Task Generation"
3. Review project context and team
4. Click "🚀 Generate Tasks with AI"
5. Review and edit generated tasks
6. Save tasks to your project

See: [AI Integration Guide](docs/features/PUTER_INTEGRATION.md)

---

## 📚 Documentation

Comprehensive documentation is available in the [`docs/`](docs/) directory:

- **[📖 Documentation Index](docs/README.md)** - Complete documentation overview
- **[🏗️ Project Structure](docs/PROJECT_STRUCTURE.md)** - Architecture and organization guide
- **[⚡ Quick Reference](docs/QUICK_REFERENCE.md)** - Common commands and shortcuts
- **[🚀 Installation Guide](docs/setup/INSTALLATION.md)** - Detailed setup instructions
- **[🤖 AI Features](docs/features/GEMINI_INTEGRATION.md)** - AI task generation guide
- **[📝 Organization Summary](docs/ORGANIZATION_SUMMARY.md)** - Recent improvements

---

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write tests for new features
- Update documentation as needed

---

## 🔒 Security

If you discover any security vulnerabilities, please email security@growdev.com instead of using the issue tracker.

---

## � Team Contribution

We would like to acknowledge the following contributors for their work on GrowDev:

| Contributor | Role | Key Contributions |
|------------|------|-------------------|
| **Shoaib Ibna Omar** | Lead Developer | • **Core Architecture**: Project setup, Auth system, Database design<br>• **AI Integration**: Gemini API task generation<br>• **Features**: Kanban Board, SRS System, Team Management, Notifications<br>• **Frontend**: Dashboard UI, CV Management<br>• **Documentation**: Comprehensive guides and API docs |
| **Mansura Yeasmin** | Backend Developer | • **Security**: Implemented cache control headers for authenticated pages<br>• **Data Management**: Database seeding and initial data setup |

---

## �📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- Google Gemini AI
- All contributors and supporters

---

<div align="center">

**Made with ❤️ by the GrowDev Team**

[Website](https://growdev.com) • [Documentation](docs/) • [Report Bug](issues) • [Request Feature](issues)

</div>
