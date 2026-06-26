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
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🌟 Overview

GrowDev is a modern, full-stack project management platform designed for software development teams. It combines Kanban boards, AI-powered task generation, comprehensive requirement tracking, sprint management, time tracking, and team collaboration tools.

### Key Highlights

- 🤖 **AI-Powered Task Generation** using OpenRouter (200+ models)
- 📊 **Kanban Board** with drag-and-drop via SortableJS
- 📝 **SRS Documentation** with functional & non-functional requirements
- 👥 **Team Management** with role-based access control
- 📈 **Progress Tracking** with real-time updates
- 🔔 **Smart Notifications** with digest options
- 🎯 **Sprint Management** with lifecycle tracking
- ⏱️ **Time Tracking** with timer controls
- 🎨 **Animated UI** with scroll effects, page transitions & toast notifications
- 📱 **Responsive Design** works on all devices

---

## ✨ Features

### Project Management
- Create and manage multiple projects
- Kanban board with drag-and-drop functionality (SortableJS)
- Task dependencies and relationships
- Priority and status tracking
- Time estimation and tracking with timer (start/pause/resume/stop)
- Sprint planning and lifecycle management (plan, start, complete, cancel)
- Task status change approval workflow (request/review/approve/reject)
- Task activity log with full history of changes

### AI Task Generation
- Automatic task creation from project requirements
- Intelligent role assignment
- Workload balancing across team
- Smart dependency detection
- Powered by OpenRouter (GPT-4, Claude 3, Llama 2, Mistral, and 200+ models)
- See: [OpenRouter Integration Guide](docs/OPENROUTER_INTEGRATION.md)

### Requirements Management
- Software Requirements Specification (SRS) documents
- Functional requirements tracking
- Non-functional requirements with metrics
- Acceptance criteria management
- Traceability between requirements and tasks
- SRS PDF export

### Team Collaboration
- Team creation and management
- Role-based permissions (10+ system roles)
- Team member invitations with token-based links and expiry
- Per-task threaded comments
- Activity tracking and full history
- Real-time notifications

### Sprint Management
- Sprint planning with goal setting
- Lifecycle management (plan, start, complete, cancel)
- Automatic progress tracking
- Task assignment across sprints

### Documentation
- Project documentation with templates (SRS, SDD, etc.)
- Mermaid diagram editor with SVG export and syntax validation
- Markdown editor
- Documentation version history and cloning

### User Profiles & Settings
- Professional profile with skills and certifications
- Education and work experience tracking
- Project portfolio
- Theme preference (dark/light mode)
- CV/Profile PDF export
- Two-factor authentication (TOTP)
- Email digest preferences (frequency, time, day)

### Global Search
- Full-text search across projects, tasks, and users

### Admin Panel
- Admin dashboard with system overview
- Data export functionality
- CI/CD scaffold gate integration

### UI/UX Enhancements
- Toast notification system (anime.js)
- Scroll-triggered animations (AOS)
- Form animations (floating labels, validation, loading states)
- Typing text effects (Typed.js)
- Button ripples, card hover effects, counter animations
- Animated progress bars and page transitions

---

## 🛠 Tech Stack

### Backend
- **Laravel 11.x** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Queue** - Background Jobs
- **Laravel Notifications** - Email & Digest System
- **Laravel Echo** - Real-time Events

### Frontend
- **Blade Templates** - Server-side Rendering
- **Tailwind CSS 3.x** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript Framework
- **Vite 7.x** - Modern Build Tool
- **Axios** - HTTP Client
- **SortableJS** - Drag-and-drop
- **anime.js** - Animation library
- **AOS** - Scroll animations
- **Typed.js** - Typing text effects
- **Mermaid** - Diagram rendering

### AI Integration
- **OpenRouter** - Multi-model AI gateway (GPT-4, Claude 3, Llama 2, Mistral, 200+ models)
- **Backend Proxy** - Secure AI API calls through Laravel

### Development Tools
- **PHPUnit** - Testing Framework
- **Laravel Pint** - Code Style
- **NPM** - Package Management
- **Git** - Version Control

### Deployment
- **Vercel** - Frontend hosting
- **Heroku** - Backend hosting

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

# AI Configuration (OpenRouter)
OPENROUTER_API_KEY=your-api-key-here
VITE_OPENROUTER_API_KEY=your-api-key-here
VITE_OPENROUTER_MODEL=openai/gpt-4o-mini

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

See: [OpenRouter Integration Guide](docs/OPENROUTER_INTEGRATION.md)

---

## 📚 Documentation

Comprehensive documentation is available in the [`docs/`](docs/) directory:

- **[📖 Documentation Index](docs/README.md)** - Complete documentation overview
- **[🏗️ Project Structure](docs/PROJECT_STRUCTURE.md)** - Architecture and organization guide
- **[⚡ Quick Reference](docs/QUICK_REFERENCE.md)** - Common commands and shortcuts
- **[🚀 Installation Guide](docs/setup/INSTALLATION.md)** - Detailed setup instructions
- **[🤖 AI Features](docs/OPENROUTER_INTEGRATION.md)** - OpenRouter AI integration guide
- **[🎨 UI Animations](docs/UI_ANIMATIONS.md)** - Animation system documentation
- **[📝 Organization Summary](docs/ORGANIZATION_SUMMARY.md)** - Recent improvements
- **[🚀 Deployment](docs/VERCEL_DEPLOYMENT.md)** - Vercel/Heroku deployment guide

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

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- OpenRouter AI
- All contributors and supporters

---

<div align="center">

**Made with ❤️ by Shoaib Ibna Omar**

[Website](https://growdev.com) • [Documentation](docs/) • [Report Bug](issues) • [Request Feature](issues)

</div>
