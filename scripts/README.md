# Scripts Directory

This directory contains utility scripts for managing and maintaining the GrowDev application.

## Available Scripts

### Database Management

#### `export-database.php`
Exports the database to a SQL file for backup purposes.

```bash
php scripts/export-database.php
```

### User Management

#### `list-users.php`
Lists all users in the system with their details.

```bash
php scripts/list-users.php
```

### System Checks

#### `check-dashboard.php`
Validates dashboard functionality and displays system statistics.

```bash
php scripts/check-dashboard.php
```

#### `check-teams.php`
Checks team configurations and relationships.

```bash
php scripts/check-teams.php
```

#### `check-all-teams.php`
Comprehensive team validation across all projects.

```bash
php scripts/check-all-teams.php
```

## Usage Notes

### Prerequisites
- Ensure you're in the project root directory
- Database must be configured and accessible
- PHP CLI must be available

### Running Scripts

All scripts should be run from the project root:

```bash
# From project root
php scripts/script-name.php
```

### Common Issues

**Database Connection Error**
- Verify `.env` database credentials
- Ensure database server is running

**Permission Denied**
```bash
chmod +x scripts/*.php  # Linux/Mac
```

## Creating New Scripts

When creating new utility scripts:

1. Place them in the `scripts/` directory
2. Add proper documentation header
3. Include error handling
4. Update this README
5. Follow existing script patterns

### Script Template

```php
<?php

/**
 * Script Name: Your Script Name
 * Description: What this script does
 * Usage: php scripts/your-script.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Your script logic here
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
```

## Best Practices

1. **Always test scripts** in development first
2. **Backup data** before running destructive scripts
3. **Log actions** for audit purposes
4. **Handle errors** gracefully
5. **Document parameters** and expected behavior

## Maintenance

Scripts are maintained by the development team. For issues or feature requests, please open an issue in the project repository.

---

Last updated: 2025-12-15
