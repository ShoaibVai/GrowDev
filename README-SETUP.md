# GrowDev - Setup Guide

## 🚀 Quick Setup

### Prerequisites
Before running the setup, ensure you have:

- **PHP 8.1+** - [Download here](https://windows.php.net/download/)
- **Composer** - [Download here](https://getcomposer.org/download/)
- **Node.js 18+** - [Download here](https://nodejs.org/)
- **NPM** (comes with Node.js)

### Automated Setup

1. **Run the setup script:**
   ```batch
   setup.bat
   ```

   This will:
   - ✅ Check system requirements
   - ✅ Create `.env` from `.env.example`
   - ✅ Install PHP dependencies (Laravel, Inertia, etc.)
   - ✅ Install JavaScript dependencies (Vue, Tailwind, etc.)
   - ✅ Generate application key
   - ✅ Create storage symlinks
   - ✅ Clear and optimize caches
   - ✅ Build production assets

2. **Set up Supabase database:**
   - Go to [Supabase Dashboard](https://supabase.com/dashboard)
   - Navigate to your project: `bwrxvijpmhnuevdrtxcy`
   - Open SQL Editor
   - Run the schema: `database/migrations/supabase_schema.sql`

3. **Start development:**
   ```batch
   dev.bat       # Development mode (recommended)
   # OR
   run.bat       # Production mode
   ```

## 🛠️ Manual Setup (Alternative)

If you prefer manual setup:

### 1. Environment Configuration
```batch
copy .env.example .env
```

### 2. Install Dependencies
```batch
composer install
npm install
```

### 3. Laravel Configuration
```batch
php artisan key:generate
php artisan storage:link
php artisan config:clear
```

### 4. Build Assets
```batch
npm run build    # Production
# OR
npm run dev      # Development (watch mode)
```

### 5. Start Server
```batch
php artisan serve
```

## 📁 Project Structure

```
GrowDev/
├── app/
│   ├── Http/Controllers/        # Laravel controllers
│   │   ├── Auth/               # Authentication controllers
│   │   └── ProjectController.php # Project management
│   └── Services/               # Business logic
│       └── SupabaseServiceEnhanced.php # Supabase integration
├── resources/
│   ├── js/
│   │   ├── pages/              # Vue.js pages (Inertia)
│   │   ├── components/         # Reusable Vue components
│   │   ├── stores/             # Pinia state management
│   │   └── services/           # Frontend services
│   └── css/
│       └── app.css             # Tailwind CSS
├── database/
│   └── migrations/
│       └── supabase_schema.sql  # Database schema
├── public/
│   └── build/                  # Built assets (generated)
├── setup.bat                   # Full setup script
├── dev.bat                     # Development mode
└── run.bat                     # Production mode
```

## 🌐 Available Scripts

| Script | Purpose | Usage |
|--------|---------|-------|
| `setup.bat` | Full project setup | Run once to set up everything |
| `dev.bat` | Development server | Hot reload + asset watching |
| `run.bat` | Production server | Optimized for production |

## 🔧 Environment Variables

The `.env` file is automatically configured with:

### Application
- `APP_NAME=GrowDev`
- `APP_URL=http://localhost:8000`
- `APP_KEY=` (auto-generated)

### Database (Supabase)
- `DB_CONNECTION=pgsql`
- `DB_HOST=db.bwrxvijpmhnuevdrtxcy.supabase.co`
- `SUPABASE_URL=https://bwrxvijpmhnuevdrtxcy.supabase.co`
- `SUPABASE_ANON_KEY=` (pre-configured)
- `SUPABASE_SERVICE_ROLE_KEY=` (pre-configured)

### Frontend (Vite)
- `VITE_SUPABASE_URL=${SUPABASE_URL}`
- `VITE_SUPABASE_ANON_KEY=${SUPABASE_ANON_KEY}`

## 🗄️ Database Schema

The project uses Supabase PostgreSQL with the following main tables:

- **profiles** - User profiles and authentication
- **projects** - Project management
- **project_members** - Team collaboration
- **messages** - Project communication

Run the SQL schema file in your Supabase dashboard to create these tables.

## 🚀 Development Workflow

### Development Mode (Recommended)
```batch
dev.bat
```
- Starts Vite dev server (hot reload)
- Starts Laravel server
- Real-time asset compilation
- Perfect for development

### Production Mode
```batch
run.bat
```
- Uses pre-built assets
- Optimized performance
- No hot reload
- Good for testing production builds

## 🔍 Troubleshooting

### Common Issues

1. **"PHP not found"**
   - Install PHP 8.1+ and add to PATH
   - Verify: `php --version`

2. **"Composer not found"**
   - Install Composer globally
   - Verify: `composer --version`

3. **"Node not found"**
   - Install Node.js 18+
   - Verify: `node --version`

4. **"Build failed"**
   - Check for JavaScript syntax errors
   - Run: `npm run dev` to see detailed errors

5. **"Database connection failed"**
   - Ensure Supabase schema is installed
   - Check `.env` database credentials

### Getting Help

1. Check the error message in the console
2. Verify all prerequisites are installed
3. Run `setup.bat` again if setup was incomplete
4. Check that Supabase database schema is installed

## 📚 Technology Stack

- **Backend:** Laravel 10, PHP 8.1+
- **Frontend:** Vue.js 3, Inertia.js
- **Styling:** Tailwind CSS, Headless UI
- **Database:** Supabase (PostgreSQL)
- **Build Tool:** Vite
- **State Management:** Pinia

## 🎯 Next Steps

After setup:

1. Visit `http://localhost:8000`
2. Register a new account
3. Confirm your email (check Supabase dashboard settings)
4. Create your first project
5. Start collaborating!

---

**Need help?** Check the main README.md or consult the project documentation.