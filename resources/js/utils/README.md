# JavaScript Utilities

This directory contains reusable utility functions for the GrowDev application.

## Purpose

The `utils/` directory is reserved for:
- Helper functions
- Common utilities
- Shared constants
- Data formatters
- Validators

## Usage

Import utilities in your modules:

```javascript
import { formatDate } from '../utils/dateFormatter.js';
import { validateEmail } from '../utils/validators.js';
```

## Creating New Utilities

When creating new utility files:

1. **Single responsibility** - One utility per file
2. **Pure functions** - No side effects when possible
3. **Document functions** - Add JSDoc comments
4. **Export named** - Use named exports

### Example Utility

```javascript
/**
 * Format a date to a human-readable string
 * 
 * @param {Date|string} date - Date to format
 * @param {string} format - Format string (default: 'YYYY-MM-DD')
 * @returns {string} Formatted date
 */
export function formatDate(date, format = 'YYYY-MM-DD') {
    // Implementation
}
```

## Common Patterns

### String Utilities
- `capitalize()` - Capitalize first letter
- `truncate()` - Truncate with ellipsis
- `slugify()` - Convert to URL-friendly slug

### Array Utilities
- `chunk()` - Split array into chunks
- `unique()` - Get unique values
- `groupBy()` - Group by property

### Object Utilities
- `deepClone()` - Deep clone object
- `merge()` - Merge objects
- `pick()` - Pick specific properties

### Validation Utilities
- `validateEmail()` - Email validation
- `validateUrl()` - URL validation
- `validatePhone()` - Phone validation

### Formatting Utilities
- `formatDate()` - Date formatting
- `formatCurrency()` - Currency formatting
- `formatNumber()` - Number formatting

## Best Practices

1. ✅ Keep functions small and focused
2. ✅ Write unit tests for utilities
3. ✅ Add JSDoc documentation
4. ✅ Use TypeScript types if applicable
5. ✅ Handle edge cases
6. ✅ Return predictable types

## Examples

See the `examples/` directory (if available) for usage examples.

---

Last updated: 2025-12-15
