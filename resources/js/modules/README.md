# JavaScript Modules

This directory contains feature-specific JavaScript modules for the GrowDev application.

## Structure

Each module typically represents a major feature or functionality:

```
modules/
├── ai-tasks.js          # AI task generation
├── kanban-board.js      # Kanban board functionality
├── notifications.js     # Notification system
└── ... more modules
```

## Module Pattern

Each module should follow this structure:

```javascript
/**
 * Module: Feature Name
 * Description: What this module does
 */

import { someService } from '../services/someService.js';
import { someUtil } from '../utils/someUtil.js';

/**
 * Initialize the module
 */
function init() {
    // Setup code
    setupEventListeners();
    loadInitialData();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Event listener code
}

/**
 * Load initial data
 */
async function loadInitialData() {
    // Data loading code
}

// Auto-initialize if needed
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Export public API
export { init };
```

## Current Modules

### ai-tasks.js
Handles AI-powered task generation using Puter.js.

**Features:**
- Task generation from requirements
- Team assignment
- Workload balancing

**Usage:**
```javascript
import '../modules/ai-tasks.js';
// Module auto-initializes
```

## Creating New Modules

1. **Create the module file** in this directory
2. **Define clear responsibilities** - One feature per module
3. **Use services** for business logic
4. **Use utils** for helper functions
5. **Export only what's needed**
6. **Add JSDoc documentation**

### Example: New Module

```javascript
/**
 * Module: Project Dashboard
 * Handles dashboard statistics and visualizations
 */

import { projectService } from '../services/projectService.js';
import { formatNumber } from '../utils/formatters.js';

class ProjectDashboard {
    constructor() {
        this.stats = null;
        this.charts = [];
    }

    async init() {
        await this.loadStats();
        this.renderStats();
        this.initCharts();
    }

    async loadStats() {
        this.stats = await projectService.getStats();
    }

    renderStats() {
        // Render statistics
    }

    initCharts() {
        // Initialize charts
    }
}

// Export and initialize
const dashboard = new ProjectDashboard();

export default dashboard;
export { dashboard };
```

## Module Loading

### In Blade Templates

```blade
@vite(['resources/js/modules/your-module.js'])
```

### In Vite Config

Add to `vite.config.js`:

```javascript
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/modules/your-module.js'
]
```

## Best Practices

1. ✅ **Single Responsibility** - One module, one feature
2. ✅ **Dependency Injection** - Import dependencies, don't create them
3. ✅ **Error Handling** - Always handle errors gracefully
4. ✅ **Loading States** - Show feedback during async operations
5. ✅ **Clean up** - Remove event listeners when needed
6. ✅ **Documentation** - Document public APIs

## Module Communication

### Using Custom Events

```javascript
// Module A - Dispatch event
document.dispatchEvent(new CustomEvent('task:created', {
    detail: { taskId: 123 }
}));

// Module B - Listen for event
document.addEventListener('task:created', (e) => {
    console.log('Task created:', e.detail.taskId);
});
```

### Using a Message Bus (if implemented)

```javascript
import { messageBus } from '../services/messageBus.js';

// Publish
messageBus.publish('task.created', { id: 123 });

// Subscribe
messageBus.subscribe('task.created', (data) => {
    console.log('Task created:', data);
});
```

## Testing Modules

Write tests for each module:

```javascript
// tests/modules/ai-tasks.test.js
import { describe, it, expect } from 'vitest';
import { aiTasks } from '../ai-tasks.js';

describe('AI Tasks Module', () => {
    it('should generate tasks from requirements', async () => {
        // Test code
    });
});
```

## Performance Tips

1. **Lazy load** modules when possible
2. **Debounce** expensive operations
3. **Cache** results when appropriate
4. **Use async/await** for better readability
5. **Avoid memory leaks** by cleaning up listeners

---

Last updated: 2025-12-15
