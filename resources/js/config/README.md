# JavaScript Configuration

This directory contains configuration files and constants for the frontend application.

## Purpose

The `config/` directory stores:
- Application constants
- API endpoints
- Feature flags
- Environment-specific settings
- Global configuration

## Usage

Import configuration in your modules:

```javascript
import { API_BASE_URL, API_TIMEOUT } from '../config/api.js';
import { FEATURE_FLAGS } from '../config/features.js';
```

## Configuration Files

### api.js (Example)
```javascript
/**
 * API Configuration
 */
export const API_BASE_URL = import.meta.env.VITE_API_URL || '/api';
export const API_TIMEOUT = 30000; // 30 seconds
export const API_VERSION = 'v1';

export const ENDPOINTS = {
    TASKS: '/tasks',
    PROJECTS: '/projects',
    USERS: '/users',
    // ... more endpoints
};
```

### features.js (Example)
```javascript
/**
 * Feature Flags
 */
export const FEATURE_FLAGS = {
    AI_TASK_GENERATION: true,
    DARK_MODE: false,
    NOTIFICATIONS: true,
    REAL_TIME_UPDATES: false,
};
```

### constants.js (Example)
```javascript
/**
 * Application Constants
 */
export const TASK_STATUSES = {
    TODO: 'To Do',
    IN_PROGRESS: 'In Progress',
    REVIEW: 'Review',
    BLOCKED: 'Blocked',
    DONE: 'Done',
};

export const PRIORITIES = {
    LOW: 'Low',
    MEDIUM: 'Medium',
    HIGH: 'High',
    CRITICAL: 'Critical',
};

export const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
export const ALLOWED_FILE_TYPES = ['.pdf', '.doc', '.docx'];
```

## Environment Variables

Access environment variables using Vite's import.meta.env:

```javascript
const apiUrl = import.meta.env.VITE_API_URL;
const isDev = import.meta.env.DEV;
const isProd = import.meta.env.PROD;
```

### Available Environment Variables

- `VITE_API_URL` - API base URL
- `VITE_APP_NAME` - Application name
- `VITE_PUSHER_KEY` - Pusher key (if using)
- Custom variables prefixed with `VITE_`

## Best Practices

1. ✅ Use UPPERCASE for constants
2. ✅ Group related configurations
3. ✅ Document configuration options
4. ✅ Use environment variables for sensitive data
5. ✅ Provide default values
6. ✅ Keep configuration DRY (Don't Repeat Yourself)

## Security Notes

⚠️ **Never commit sensitive data** like:
- API keys
- Passwords
- Secret tokens
- Private credentials

Use environment variables and `.env` files instead.

## Type Safety (Optional)

Consider adding TypeScript definitions:

```typescript
// config.d.ts
export interface APIConfig {
    baseUrl: string;
    timeout: number;
    version: string;
}
```

---

Last updated: 2025-12-15
