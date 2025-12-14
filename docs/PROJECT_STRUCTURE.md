# Project Structure Guide

This document provides a comprehensive overview of the GrowDev project structure and organization.

## 📁 Directory Structure

```
GrowDev/
│
├── 📱 app/                          # Application Core
│   ├── Console/                     # Artisan commands
│   │   ├── Commands/                # Custom commands
│   │   └── Kernel.php               # Console kernel
│   ├── Events/                      # Event classes
│   │   └── TaskUpdated.php
│   ├── Http/                        # HTTP Layer
│   │   ├── Controllers/             # Request handlers
│   │   │   ├── AITaskController.php
│   │   │   ├── ProjectController.php
│   │   │   └── ...
│   │   ├── Middleware/              # HTTP middleware
│   │   └── Requests/                # Form request validation
│   ├── Models/                      # Eloquent models
│   │   ├── Project.php
│   │   ├── Task.php
│   │   ├── User.php
│   │   └── ...
│   ├── Notifications/               # Notification classes
│   │   ├── TaskAssigned.php
│   │   ├── DigestNotification.php
│   │   └── ...
│   ├── Policies/                    # Authorization policies
│   │   ├── ProjectPolicy.php
│   │   ├── TaskPolicy.php
│   │   └── ...
│   ├── Providers/                   # Service providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   └── ...
│   ├── Services/                    # Business logic services
│   │   ├── AI/                      # AI-related services
│   │   │   └── TaskGenerationService.php
│   │   └── PasswordResetService.php
│   └── View/                        # View composers
│
├── 🗄️ database/                      # Database Layer
│   ├── factories/                   # Model factories for testing
│   ├── migrations/                  # Database migrations
│   │   └── 2025_12_*_*.php
│   └── seeders/                     # Database seeders
│       ├── DatabaseSeeder.php
│       ├── ShowcaseDataSeeder.php
│       └── SystemRolesSeeder.php
│
├── 📚 docs/                          # Documentation
│   ├── api/                         # API documentation
│   ├── features/                    # Feature guides
│   │   └── GEMINI_INTEGRATION.md
│   └── setup/                       # Setup & installation
│       ├── INSTALLATION.md
│       └── SEED_DATA.txt
│
├── 🌐 public/                        # Public Assets
│   ├── index.php                    # Application entry point
│   ├── robots.txt
│   └── build/                       # Compiled assets (generated)
│       ├── assets/
│       └── manifest.json
│
├── 🎨 resources/                     # Frontend Resources
│   ├── css/                         # Stylesheets
│   │   └── app.css                  # Main stylesheet
│   ├── js/                          # JavaScript
│   │   ├── modules/                 # Feature modules
│   │   │   ├── ai-tasks.js
│   │   │   └── README.md
│   │   ├── services/                # JavaScript services
│   │   │   └── geminiAI.js
│   │   ├── utils/                   # Utility functions
│   │   │   └── README.md
│   │   ├── config/                  # JS configuration
│   │   │   └── README.md
│   │   ├── app.js                   # Main JS entry
│   │   └── bootstrap.js             # Bootstrap & Axios
│   └── views/                       # Blade templates
│       ├── layouts/                 # Layout templates
│       ├── components/              # Reusable components
│       ├── projects/                # Project views
│       │   ├── ai-tasks/
│       │   │   └── preview.blade.php
│       │   └── ...
│       └── ...
│
├── 🛤️ routes/                        # Application Routes
│   ├── web.php                      # Web routes
│   ├── api.php                      # API routes
│   ├── auth.php                     # Authentication routes
│   ├── channels.php                 # Broadcast channels
│   └── console.php                  # Console routes
│
├── 🔧 scripts/                       # Utility Scripts
│   ├── check-all-teams.php
│   ├── check-dashboard.php
│   ├── check-teams.php
│   ├── export-database.php
│   ├── list-users.php
│   └── README.md
│
├── 📦 storage/                       # Storage Layer
│   ├── app/                         # Application files
│   │   ├── public/                  # Publicly accessible files
│   │   └── private/                 # Private files
│   ├── framework/                   # Framework files
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/                        # Application logs
│       └── laravel.log
│
├── 🧪 tests/                         # Automated Tests
│   ├── Feature/                     # Feature tests
│   │   ├── TaskTest.php
│   │   ├── ProjectTest.php
│   │   └── ...
│   ├── Unit/                        # Unit tests
│   └── TestCase.php                 # Base test case
│
├── ⚙️ config/                        # Configuration Files
│   ├── app.php                      # Application config
│   ├── auth.php                     # Authentication config
│   ├── database.php                 # Database config
│   ├── mail.php                     # Mail config
│   ├── queue.php                    # Queue config
│   ├── services.php                 # Third-party services
│   └── ...
│
├── 📄 Root Files                     # Configuration & Docs
│   ├── .editorconfig                # Editor configuration
│   ├── .env                         # Environment variables (not in git)
│   ├── .env.example                 # Example environment
│   ├── .gitattributes               # Git attributes
│   ├── .gitignore                   # Git ignore rules
│   ├── artisan                      # Artisan CLI
│   ├── composer.json                # PHP dependencies
│   ├── package.json                 # Node dependencies
│   ├── phpunit.xml                  # PHPUnit config
│   ├── postcss.config.js            # PostCSS config
│   ├── setup.bat                    # Windows setup script
│   ├── setup.sh                     # Unix setup script
│   ├── tailwind.config.js           # Tailwind CSS config
│   ├── vite.config.js               # Vite build config
│   ├── README.md                    # Main README
│   ├── CHANGELOG.md                 # Version history
│   └── CONTRIBUTING.md              # Contribution guidelines
│
└── 📦 Dependencies (auto-generated)
    ├── vendor/                      # Composer dependencies
    ├── node_modules/                # NPM dependencies
    └── bootstrap/cache/             # Framework cache
```

## 📖 Key Directory Explanations

### `/app` - Application Core

The heart of your Laravel application containing all business logic.

**Key Subdirectories:**
- **Console**: Custom Artisan commands for CLI operations
- **Events**: Event classes for the event-driven architecture
- **Http**: Controllers, middleware, and form requests
- **Models**: Eloquent ORM models representing database tables
- **Notifications**: Email and notification classes
- **Policies**: Authorization logic for models
- **Providers**: Service container bindings and bootstrapping
- **Services**: Reusable business logic (recommended pattern)

### `/database` - Database Layer

All database-related files including migrations, seeders, and factories.

**Key Files:**
- **migrations/**: Database schema definitions (versioned)
- **seeders/**: Test and demo data insertion
- **factories/**: Model factories for testing and seeding

### `/docs` - Documentation

**New organized documentation structure:**
- **api/**: API endpoint documentation
- **features/**: Feature-specific guides (e.g., AI integration)
- **setup/**: Installation and configuration guides

### `/public` - Public Web Root

The only directory exposed to the web server.

**Important:**
- Never put sensitive files here
- Contains compiled assets in `/build`
- Entry point: `index.php`

### `/resources` - Frontend Resources

All frontend code before compilation.

**Organized JavaScript Structure:**
```
js/
├── modules/     # Feature-specific modules (ai-tasks, kanban, etc.)
├── services/    # Business logic services (API clients, etc.)
├── utils/       # Helper functions and utilities
└── config/      # Configuration and constants
```

### `/routes` - Route Definitions

**Route Files:**
- **web.php**: Web interface routes (session-based auth)
- **api.php**: API routes (token-based auth)
- **auth.php**: Authentication routes (login, register, etc.)
- **channels.php**: Broadcast channel authorization

### `/scripts` - Utility Scripts

Custom PHP scripts for maintenance and debugging tasks.

**Common uses:**
- Database exports
- System checks
- User management
- Data migrations

### `/storage` - File Storage

**Subdirectories:**
- **app/**: Application files (uploads, generated files)
- **framework/**: Framework cache, sessions, views
- **logs/**: Application logs

### `/tests` - Automated Tests

**Test Types:**
- **Feature**: End-to-end tests
- **Unit**: Isolated component tests

## 🏗️ Architecture Patterns

### MVC Architecture

```
┌─────────┐
│  Route  │  → routes/web.php
└────┬────┘
     │
     ▼
┌─────────────┐
│ Controller  │  → app/Http/Controllers/
└──────┬──────┘
       │
       ├─────────────┐
       │             │
       ▼             ▼
┌─────────┐    ┌─────────┐
│  Model  │    │  View   │
└─────────┘    └─────────┘
app/Models/    resources/views/
```

### Service Pattern

For complex business logic:

```
Controller
    └─→ Service (app/Services/)
            └─→ Model
```

### Repository Pattern (Optional)

For data abstraction:

```
Controller
    └─→ Repository
            └─→ Model
```

## 🎯 Best Practices

### 1. **File Naming Conventions**

- **Controllers**: `PascalCase` with `Controller` suffix
  - Example: `TaskController.php`
- **Models**: `PascalCase`, singular
  - Example: `Task.php`, `User.php`
- **Views**: `kebab-case.blade.php`
  - Example: `create-project.blade.php`
- **JavaScript**: `camelCase.js`
  - Example: `aiTasks.js`, `geminiAI.js`

### 2. **Code Organization**

✅ **DO:**
- Group related functionality
- Use services for complex business logic
- Keep controllers thin
- Use form requests for validation
- Write tests for new features

❌ **DON'T:**
- Put business logic in controllers
- Repeat code (DRY principle)
- Mix concerns (separation of concerns)
- Commit sensitive data

### 3. **File Locations**

| Type | Location | Example |
|------|----------|---------|
| Business Logic | `app/Services/` | `TaskGenerationService.php` |
| Validation Rules | `app/Http/Requests/` | `StoreTaskRequest.php` |
| API Resources | `app/Http/Resources/` | `TaskResource.php` |
| View Components | `app/View/Components/` | `TaskCard.php` |
| JS Modules | `resources/js/modules/` | `ai-tasks.js` |
| Utilities | `resources/js/utils/` | `formatters.js` |

## 📝 Adding New Features

### Step-by-step Guide:

1. **Create Migration**
   ```bash
   php artisan make:migration create_feature_table
   ```

2. **Create Model**
   ```bash
   php artisan make:model Feature -mfcs
   # -m: migration, -f: factory, -c: controller, -s: seeder
   ```

3. **Create Controller**
   ```bash
   php artisan make:controller FeatureController --resource
   ```

4. **Add Routes** in `routes/web.php`

5. **Create Views** in `resources/views/features/`

6. **Add Tests** in `tests/Feature/`

7. **Update Documentation** in `docs/`

## 🔍 Finding Code

### Common Locations:

**"Where is the X feature?"**

| Feature | Location |
|---------|----------|
| User authentication | `routes/auth.php`, `app/Http/Controllers/Auth/` |
| Task management | `app/Http/Controllers/TaskController.php` |
| AI task generation | `app/Services/AI/`, `resources/js/modules/ai-tasks.js` |
| Email notifications | `app/Notifications/` |
| Database schema | `database/migrations/` |
| Frontend assets | `resources/js/`, `resources/css/` |
| Configuration | `config/` |
| Tests | `tests/Feature/`, `tests/Unit/` |

## 🚀 Performance Tips

1. **Use Eager Loading** to prevent N+1 queries
2. **Cache Configuration** in production
3. **Optimize Autoloader** with `composer dump-autoload -o`
4. **Use Queue** for slow operations
5. **Compile Assets** for production: `npm run build`

## 📚 Further Reading

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/)
- [Project README](../README.md)

---

Last updated: 2025-12-15
