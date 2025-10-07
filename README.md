# GrowDev - Project Management Platform

A comprehensive Laravel + Vue.js project management platform with Supabase backend integration, designed to guide users through software development processes.

## 🚀 Features

- **Team & Solo Project Management**: Create and manage development projects
- **Role-Based Assignment**: Developer, UI/UX, Tester, Project Manager roles
- **Project Stages**: Guided workflow from Idea → Planning → Design → Development → Testing → Deployment
- **Documentation Templates**: SRS, README, development log templates
- **Real-time Chat**: Per-project messaging system
- **Shared Databases**: Team collaboration with Supabase
- **Analytics**: Laravel Telescope integration

## 🛠 Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Vue.js 3 with Composition API
- **Database & Auth**: Supabase (PostgreSQL)
- **Storage**: Supabase Storage
- **Real-time**: Supabase Realtime
- **Styling**: Tailwind CSS
- **Build Tool**: Vite

## 📋 Prerequisites

- PHP 8.3+
- Composer 2.7+
- Node.js 20+
- Git

## 🚀 Quick Start

### 1. Clone & Install Dependencies

```bash
git clone https://github.com/shoaibomar/GrowDev.git
cd GrowDev
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Environment

Update your `.env` file with the provided Supabase credentials:

```env
SUPABASE_URL=https://bwrxvijpmhnuevdrtxcy.supabase.co
SUPABASE_ANON_KEY=your_anon_key_here
SUPABASE_SERVICE_ROLE_KEY=your_service_role_key_here
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build Assets & Start Development

```bash
npm run dev
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📁 Project Structure

```
GrowDev/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── ...
├── resources/
│   ├── js/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── stores/
│   │   └── app.js
│   └── views/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── routes/
└── ...
```

## 🔧 Development Commands

```bash
# Start development server
php artisan serve

# Build assets for development
npm run dev

# Build assets for production
npm run build

# Run tests
php artisan test

# Clear caches
php artisan optimize:clear
```

## 📚 Documentation

- [Installation Guide](docs/installation.md)
- [API Documentation](docs/api.md)
- [Contributing Guidelines](docs/contributing.md)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team

- **Repository**: [github.com/shoaibomar/GrowDev](https://github.com/shoaibomar/GrowDev)
- **Environment**: Local + Production Ready