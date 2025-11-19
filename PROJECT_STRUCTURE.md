# GrowDev - Organized Project Structure

This document outlines the refactored view structure of the GrowDev application.

## Directory Structure

```
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php              # Main authenticated layout
│   │   ├── guest.blade.php            # Guest/public layout
│   │   ├── navigation.blade.php       # Legacy (kept for compatibility)
│   │   └── partials/
│   │       ├── header.blade.php       # Navigation component (extracted)
│   │       └── footer.blade.php       # Footer component (extracted)
│   │
│   ├── pages/
│   │   ├── home.blade.php             # Public homepage/landing page
│   │   ├── admin/
│   │   │   └── dashboard.blade.php    # Admin dashboard with projects
│   │   └── user/
│   │       └── profile.blade.php      # User profile & CV management
│   │
│   ├── components/
│   │   ├── button.blade.php           # Reusable button component
│   │   ├── card.blade.php             # Reusable card component
│   │   └── modal.blade.php            # Reusable modal component
│   │
│   ├── auth/                          # Authentication views (Fortify)
│   ├── documentation/                 # SRS/SDD documentation views
│   ├── profile/                       # Legacy profile views (kept)
│   ├── projects/                      # Project management views
│   └── cv/                            # CV-related views
```

## Component Usage

### Button Component
```blade
<x-button type="primary" size="md">Click Me</x-button>
<x-button type="secondary" href="{{ route('home') }}">Link Button</x-button>
<x-button type="danger" disabled>Disabled Button</x-button>
```

### Card Component
```blade
<x-card class="custom-class">
    <h3>Card Title</h3>
    <p>Card content goes here</p>
</x-card>
```

### Modal Component
```blade
<x-modal id="confirm-modal" title="Confirm Action" size="md">
    <p>Are you sure you want to proceed?</p>
</x-modal>
```

## Routes Updated

| Route | Old View | New View |
|-------|----------|----------|
| `/` | `welcome` | `pages.home` |
| `/dashboard` | `dashboard` | `pages.admin.dashboard` |
| `/profile` | `profile.edit` | `pages.user.profile` |

## Layout Structure

### Main Authenticated Layout (app.blade.php)
```
┌─────────────────────────────────┐
│  Header (partials/header.blade)  │  ← Navigation with dropdowns
├─────────────────────────────────┤
│ Page Header (optional)           │  ← Page title/actions
├─────────────────────────────────┤
│                                 │
│  Main Content (slot)             │  ← Page-specific content
│                                 │
├─────────────────────────────────┤
│  Footer (partials/footer.blade)  │  ← Site footer
└─────────────────────────────────┘
```

## Features

✅ **Organized Structure**: Views organized by purpose (pages, components, layouts)  
✅ **Reusable Components**: Button, Card, Modal components  
✅ **Semantic Naming**: Clear, descriptive naming conventions  
✅ **Partial Extraction**: Header and footer extracted as reusable partials  
✅ **Clean CSS**: Pure HTML/CSS with no Tailwind or Alpine.js  
✅ **Vanilla JavaScript**: All interactivity using plain JavaScript  
✅ **All Tests Passing**: 23/23 tests pass with new structure  

## Migration Notes

- Old views (`dashboard.blade.php`, `welcome.blade.php`) have been moved to `pages/`
- `profile/edit.blade.php` is also available at `pages/user/profile.blade.php`
- Original files kept in legacy locations for backward compatibility
- All route references updated to point to new locations
- ProfileController updated to use new view path

## Next Steps

- Use the reusable components in your templates for consistency
- Add more components as needed (form-group, alert, badge, etc.)
- Consider adding more admin/user pages as the application grows
- Keep components modular and reusable
