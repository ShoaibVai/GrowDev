# GrowDev AI Coding Agent Instructions

## Project Overview
GrowDev is a Laravel 12 project management platform for software development teams with AI-powered task generation, SRS documentation, team collaboration, and Kanban boards.

## Critical Architecture Patterns

### Task Status Approval Workflow
Tasks use a two-step status change approval system. When assignees want to change status (especially to "Done"), they create a `TaskStatusRequest` that project owners must approve/reject. Never directly update task status without checking `TaskStatusRequest` relationships.

```php
// Task model has pendingStatusRequest relationship
$task->pendingStatusRequest; // Check before status updates
```

### Polymorphic Requirement Linking
Tasks link to SRS requirements via polymorphic relationships:
```php
'requirement_type' => 'App\Models\SrsFunctionalRequirement',
'requirement_id' => 123
```
Use `requirement_type` and `requirement_id` together, never separately.

### AI Integration (Client-Side)
AI task generation uses **Google Gemini API directly** running CLIENT-SIDE in the browser, not backend. See `resources/js/services/geminiAI.js`. Frontend directly calls Gemini API with key from `.env`. Legacy backend service exists but is deprecated.

## Development Workflows

### Running the Application
```bash
# One command to run everything (server + queue + logs + vite)
composer dev

# Or setup from scratch
composer setup   # Installs deps, copies .env, generates key, runs migrations

# Queue worker (essential for notifications/background jobs)
php artisan queue:work
```

### Database Management
- Always use migrations (never manual schema changes)
- `php artisan migrate:fresh --seed` for clean state with demo data
- Seeders: `ShowcaseDataSeeder` provides comprehensive demo data, `SystemRolesSeeder` for predefined roles
- Default admin: `admin@growdev.com` / `password`

### Frontend Build
- **Vite 7.x** (not Webpack) - configured in `vite.config.js`
- `npm run dev` - hot reload during development
- `npm run build` - production build
- Assets compiled to `public/build/`

## File Organization Conventions

### JavaScript Architecture
- **Modules**: `resources/js/modules/` - Feature-specific code (ai-tasks.js, kanban-board.js, toast.js, etc.)
- **Services**: `resources/js/services/` - Reusable services (geminiAI.js for AI integration)
- **Utils**: `resources/js/utils/` - Utility functions
- **Config**: `resources/js/config/` - JS configuration
- Each module auto-initializes and exports public API

### Backend Service Layer
Place complex business logic in `app/Services/` (e.g., `PasswordResetService.php`, `AI/TaskGenerationService.php`). Controllers should be thin - delegate to services.

### Authorization
Use Policies consistently (ProjectPolicy, TaskPolicy, etc.). Always check authorization in controllers: `$this->authorize('update', $project)`. Project ownership = `user_id` field, not team membership.

### API Routes
- Web routes in `routes/web.php` - Session-based auth
- API routes in `routes/api.php` - Token-based auth (Sanctum)
- User search endpoint available at `/web-api/users/search` for AJAX calls from web views

## Domain Model Key Relationships

### Projects
- Belongs to User (owner via `user_id`)
- Optionally belongs to Team (via `team_id`)
- Has many Tasks, SrsDocuments
- Status workflow: Planning → In Progress → Review → Completed → Archived

### Teams
- Has many Users through pivot table (`team_user`) with roles
- Roles stored in `roles` table (10+ predefined system roles)
- Invitation system via `invitations` table with token-based acceptance

### SRS Documents
Comprehensive fields (38+ properties) including:
- `purpose`, `document_conventions`, `intended_audience`, `product_scope`
- `functional_requirements` and `non_functional_requirements` as separate related models
- Each requirement can have multiple Tasks linked to it

### Notifications
- Queue-based delivery (uses `database` queue driver)
- Digest system: users can batch notifications (daily/weekly)
- Types: TaskAssigned, TaskStatusChanged, TaskStatusChangeRequested, TaskStatusRequestReviewed, TeamInvitation, DigestNotification

## Animation System
Project uses **multiple animation libraries**:
- **anime.js** - Core animations
- **GSAP** - Advanced animations
- **AOS** - Scroll animations
- **Typed.js** - Typing effects
- **Particles.js** - Background effects

All modules in `resources/js/modules/` (animations.js, scroll-animations.js, etc.). Components have data attributes for animations (e.g., `data-aos="fade-up"`).

## Common Pitfalls

1. **Don't bypass Task approval workflow** - Always use TaskStatusRequest for status changes from assignee
2. **Queue must run** - Notifications won't send without `php artisan queue:work`
3. **Polymorphic relationships** - Set both `requirement_type` and `requirement_id` when linking tasks
4. **AI is client-side** - Don't add backend API routes for Gemini; frontend handles it directly via `geminiAI.js`
5. **File paths** - Use workspace-relative paths in docs, not absolute Windows paths
6. **Mass assignment** - All models use `$fillable`, never `$guarded`

## Testing
- PHPUnit configured in `phpunit.xml`
- Tests in `tests/Feature/` and `tests/Unit/`
- Run: `php artisan test` or `composer test`
- Factories available for User, Project, Team models

## Configuration Files
- `.env` - Environment config (copy from `.env.example`)
- `config/services.php` - Gemini API config under `'gemini'` key
- `tailwind.config.js` - Tailwind CSS customization
- `vite.config.js` - Build configuration

## Documentation
- `docs/` - Comprehensive guides organized by type (features/, setup/, api/)
- `docs/QUICK_REFERENCE.md` - Common commands
- `docs/PROJECT_STRUCTURE.md` - Architecture overview
- `docs/features/GEMINI_INTEGRATION.md` - AI integration guide (Google Gemini API)

## Key Commands Reference
```bash
# Development
composer dev                          # Run all services concurrently
php artisan serve                     # Just web server
php artisan queue:work               # Background jobs (required!)

# Database
php artisan migrate:fresh --seed      # Reset with demo data
php artisan make:migration <name>     # Create migration
php artisan make:model <Name> -mfs   # Model + migration + factory + seeder

# Code Quality
php artisan pint                      # Laravel Pint code style fixer
php artisan test                      # Run tests

# Assets
npm run dev                           # Development with hot reload
npm run build                         # Production build
```
