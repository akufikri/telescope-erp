# Module System Guideline

## Overview

Module system mengontrol visibility navigation group di admin panel berdasarkan `ACTIVE_MODULES` di `.env`. Self-hosted client hanya melihat module yang diaktifkan.

## Architecture

```
.env (ACTIVE_MODULES)
    ↓
config/modules.php → parses comma-separated list
    ↓
ModuleFilter service → resolves active modules
    ↓
NavigationGroup::isActive() → checks group visibility
    ↓
ModuleAwareNavigationManager → filters navigation at runtime
```

---

## Configuration

### .env

```env
# Semua module aktif
ACTIVE_MODULES=all

# Module spesifik
ACTIVE_MODULES=sale,inventory,accounting

# Single module
ACTIVE_MODULES=manufacturing
```

### config/modules.php

```php
'active' => array_filter(
    array_map('trim', explode(',', env('ACTIVE_MODULES', 'all')))
),

'mapping' => [
    'sale'          => ['sales'],
    'purchase'      => ['purchases'],
    'inventory'     => ['inventories', 'barcode'],
    'manufacturing' => ['manufacturing'],
    // ...
],

'core' => [
    'security',
    'support',
    'plugin-manager',
    'chatter',
    'fields',
    'payments',
],
```

---

## Available Modules

| Module Key | Navigation Group | Required Plugins |
|------------|-----------------|------------------|
| `dashboard` | Dashboard | analytics |
| `contact` | Contacts | contacts |
| `sale` | Sales | sales |
| `purchase` | Purchases | purchases |
| `manufacturing` | Manufacturing | manufacturing |
| `maintenance` | Maintenance | maintenance |
| `inventory` | Inventories | inventories, barcode |
| `invoice` | Invoices | invoices |
| `accounting` | Accounting | accounts, accounting |
| `project` | Projects | projects, timesheets |
| `employee` | Employees | employees |
| `time-off` | Time Off | time-off |
| `recruitment` | Recruitments | recruitments |
| `website` | Website | website, blogs |
| `barcode` | Barcode | barcode |
| `setting` | Settings | support |
| `help` | Help | support |

---

## API Reference

### ModuleFilter Service

Location: `plugins/webkul/support/src/Services/ModuleFilter.php`

```php
use Webkul\Support\Services\ModuleFilter;

// Get all active module keys
$active = ModuleFilter::getActive();
// Returns: ['sale', 'inventory', ...]

// Check if specific module is active
if (ModuleFilter::isActive('sale')) {
    // Sale module is enabled
}

// Check if navigation group should show
if (ModuleFilter::isGroupActive('inventory')) {
    // Inventory nav group visible
}

// Get all active plugin names (for service provider registration)
$plugins = ModuleFilter::getActivePlugins();
// Returns: ['security', 'support', 'sales', 'inventories', ...]

// Debug: get status of all modules
$status = ModuleFilter::getStatus();
```

### NavigationGroup Enum

Location: `plugins/webkul/support/src/Enums/NavigationGroup.php`

```php
use Webkul\Support\Enums\NavigationGroup;

// isActive() checks against ModuleFilter
NavigationGroup::Sale->isActive();      // true if 'sale' in ACTIVE_MODULES
NavigationGroup::Inventory->isActive(); // true if 'inventory' in ACTIVE_MODULES
```

---

## Adding New Module

### 1. Add case to NavigationGroup enum

```php
// plugins/webkul/support/src/Enums/NavigationGroup.php

enum NavigationGroup: string implements HasIcon, HasLabel
{
    // ... existing cases

    case Warehouse = 'warehouse';

    public function getIcon(): string
    {
        return match ($this) {
            // ... existing cases
            self::Warehouse => 'icon-warehouse',
        };
    }
}
```

### 2. Add mapping to config/modules.php

```php
'mapping' => [
    // ... existing mappings
    'warehouse' => ['warehouses', 'stock-moves'],
],
```

### 3. Add translation

```php
// lang/en/admin.php
'navigation' => [
    // ... existing translations
    'warehouse' => 'Warehouse',
],
```

### 4. Activate in .env

```env
ACTIVE_MODULES=sale,inventory,warehouse
```

---

## Adding Translation for Navigation Group

Location: `resources/lang/en/admin.php` (or respective locale)

```php
'navigation' => [
    'dashboard'     => 'Dashboard',
    'contact'       => 'Contacts',
    'sale'          => 'Sales',
    'purchase'      => 'Purchases',
    'manufacturing' => 'Manufacturing',
    'maintenance'   => 'Maintenance',
    'inventory'     => 'Inventories',
    'invoice'       => 'Invoices',
    'accounting'    => 'Accounting',
    'project'       => 'Projects',
    'employee'      => 'Employees',
    'time-off'      => 'Time Off',
    'recruitment'   => 'Recruitments',
    'website'       => 'Website',
    'barcode'       => 'Barcode',
    'setting'       => 'Settings',
    'help'          => 'Help',
],
```

---

## How Navigation Filtering Works

1. **Panel boot** → `AdminPanelProvider` registers `NavigationGroup::class`
2. **NavigationManager resolve** → `ModuleAwareNavigationManager` injected via `SupportServiceProvider`
3. **Navigation mount** → Items registered from all pages/resources
4. **Group filtering** → `get()` method filters groups by calling `$groupEnum->isActive()`
5. **Result** → Only active groups rendered in sidebar/topbar

### Key Code Path

```
ModuleAwareNavigationManager::get()
    → collect(navigationItems)
    → groupBy(serialize(group))
    → map to NavigationGroup instances
    ->filter(fn(group) => groupEnum->isActive())  // <-- filtering happens here
    → sortBy registered order
    → return visible groups
```

---

## Testing Module Filter

### In Tinker

```bash
# Test with all modules
php artisan tinker --execute="
use Webkul\Support\Services\ModuleFilter;
print_r(ModuleFilter::getActive());
"

# Test with specific modules
ACTIVE_MODULES=sale,inventory php artisan tinker --execute="
use Webkul\Support\Services\ModuleFilter;
echo 'Sale: ' . (ModuleFilter::isActive('sale') ? 'yes' : 'no') . PHP_EOL;
echo 'Purchase: ' . (ModuleFilter::isActive('purchase') ? 'yes' : 'no') . PHP_EOL;
"
```

### In Code

```php
// Check before registering navigation item
if (ModuleFilter::isActive('sale')) {
    // Register sale navigation items
}

// In a service provider
if (ModuleFilter::isGroupActive('manufacturing')) {
    $this->app->register(ManufacturingServiceProvider::class);
}
```

---

## Common Issues

### Module not showing in navigation

1. Check `ACTIVE_MODULES` in `.env` includes the module key
2. Check `config/modules.php` mapping exists for that key
3. Check `NavigationGroup` enum has the case
4. Check translation exists in `admin.php` lang file

### NavigationGroup enum case doesn't match mapping key

```php
// Enum case must match mapping key exactly
enum NavigationGroup: string
{
    case Sale = 'sale';  // matches 'sale' in mapping
}

'mapping' => [
    'sale' => ['sales'],  // key = enum case value
],
```

### Core modules always visible

Modules in `config/modules.php` `core` array are always loaded regardless of `ACTIVE_MODULES`. These are infrastructure plugins (security, support, etc.) that must always be present.
