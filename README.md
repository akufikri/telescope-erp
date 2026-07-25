<p align="center">
  <a href="https://telescopeerp.com">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/telescope-erp/temp-media/main/telescope-logo-dark.png">
      <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/telescope-erp/temp-media/main/telescope-logo-light.png">
      <img src="https://raw.githubusercontent.com/telescope-erp/temp-media/main/telescope-logo-light.png" alt="Telescope ERP logo">
    </picture>
  </a>
</p>

<h1 align="center">Telescope ERP</h1>

<p align="center">
  <strong>B2B Enterprise Resource Planning SaaS Platform</strong>
</p>

<p align="center">
  Built with Laravel 13 • Powered by FilamentPHP 5 • PHP 8.3+
</p>

---

## Table of Contents

1. [Introduction](#introduction)
2. [Architecture](#architecture)
3. [Key Features](#key-features)
4. [Requirements](#requirements)
5. [Quick Start](#quick-start)
6. [Module System](#module-system)
7. [Multi-Tenancy](#multi-tenancy)
8. [Customization](#customization)
9. [Contributing](#contributing)
10. [License](#license)
11. [Security](#security)

---

## Introduction

Telescope ERP is a B2B enterprise resource planning platform designed for SaaS deployment. Built on Laravel 13 and FilamentPHP 5, it provides a modular, plugin-based architecture where self-hosted clients can enable only the modules they need.

The system supports three user roles: super admin (vendor), company admin, and staff. Each company operates in its own tenant context with isolated data.

---

## Architecture

```
telescopeerp/
├── app/                          # Application core (panels, providers)
├── plugins/webkul/               # 28 modular plugins
│   ├── accounting/               # Financial management
│   ├── analytics/                # Business intelligence
│   ├── contacts/                 # Contact management
│   ├── employees/                # HR employee management
│   ├── inventories/              # Inventory & warehouse
│   ├── invoices/                 # Invoice generation
│   ├── manufacturing/            # BOM, work orders, work centers
│   ├── purchases/                # Procurement
│   ├── sales/                    # Sales pipeline
│   ├── support/                  # Core support (navigation, module filter)
│   └── ...                       # +18 more plugins
├── config/
│   └── modules.php               # Module activation config
└── routes/
```

### Tech Stack

| Component | Version |
|-----------|---------|
| PHP | 8.3+ |
| Laravel | 13.x |
| FilamentPHP | 5.x |
| Livewire | 3.x |
| TailwindCSS | 4.x |
| Pest | 4.x (testing) |

---

## Key Features

### Plugin-Based Module Filtering

Control which modules are visible per deployment via `ACTIVE_MODULES` in `.env`:

```env
# Enable all modules
ACTIVE_MODULES=all

# Enable only specific modules
ACTIVE_MODULES=sale,inventory,accounting
```

Available modules: `dashboard`, `contact`, `sale`, `purchase`, `manufacturing`, `maintenance`, `inventory`, `invoice`, `accounting`, `project`, `employee`, `time-off`, `recruitment`, `website`, `barcode`, `setting`

### Multi-Tenancy

- **Super Admin (Vendor)** — Manages all companies, plugins, and platform settings
- **Company Admin** — Manages their company's users, data, and configurations
- **Staff** — Operates within their assigned company context

### Modular Plugin System

28 plugins organized by business domain:

| Domain | Plugins |
|--------|---------|
| Financial | Accounting, Invoices, Payments |
| Operations | Sales, Purchases, Inventories, Manufacturing |
| HR | Employees, Time Off, Recruitments |
| CRM | Contacts, Partners |
| Projects | Projects, Timesheets |
| Other | Website, Barcode, Analytics |

### Additional Features

- Role-based access control via Filament Shield
- Multi-language support with language switcher
- Dark mode support
- Real-time updates via Livewire
- Responsive UI with TailwindCSS
- Custom quick navigation favorites
- Global search across all modules

---

## Requirements

- **PHP**: 8.3+
- **Database**: MySQL 8.0+ or SQLite 3.8.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Composer**: 2.0+
- **Node.js**: 18.x+

---

## Quick Start

### 1. Clone the repository

```bash
git clone https://github.com/telescope-erp/telescopeerp.git
cd telescopeerp
```

### 2. Install dependencies

```bash
composer install
npm install && npm run build
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` to set your database and `ACTIVE_MODULES`:

```env
DB_CONNECTION=mysql
DB_DATABASE=telescopeerp
DB_USERNAME=root
DB_PASSWORD=

ACTIVE_MODULES=all
APP_CURRENCY=IDR
```

### 4. Run installation

```bash
php artisan erp:install
```

This will:
- Run database migrations
- Seed initial data
- Generate roles and permissions (Filament Shield)
- Create admin account

### 5. Start development server

```bash
php artisan serve
```

Visit `http://localhost:8000/admin` and log in.

---

## Module System

### How Module Filtering Works

1. `config/modules.php` reads `ACTIVE_MODULES` from `.env`
2. `ModuleFilter` service resolves active modules
3. `NavigationGroup` enum checks visibility via `isActive()`
4. `ModuleAwareNavigationManager` filters navigation at runtime

### Enabling/Disabling Modules

Edit `.env`:

```env
# Sales + Inventory only
ACTIVE_MODULES=sale,inventory

# Full ERP
ACTIVE_MODULES=all
```

### Plugin Installation

Install individual plugins:

```bash
php artisan inventories:install
php artisan sales:install
```

Uninstall:

```bash
php artisan inventories:uninstall
```

Dependencies are automatically detected and prompted during installation.

---

## Multi-Tenancy

Each company operates in an isolated tenant context:

- Company data is scoped to the authenticated user's company
- Super admins can switch between companies
- Company admins manage their own staff and settings
- Staff members see only their company's data

---

## Customization

- **Modules** — Enable/disable via `ACTIVE_MODULES`
- **Navigation** — Add custom navigation groups via `NavigationGroup` enum
- **UI** — Customize Filament panels, themes, and branding
- **Roles** — Configure permissions via Filament Shield
- **Plugins** — Create custom plugins following existing plugin structure

---

## Contributing

1. Create a feature branch: `git checkout -b feature/your-feature`
2. Make changes following existing code conventions
3. Run tests: `php artisan test --compact`
4. Run Pint: `vendor/bin/pint`
5. Commit: `git commit -m "Add: description"`
6. Push and submit a pull request

---

## License

Telescope ERP is proprietary software. Unauthorized copying, modification, or distribution is prohibited.

---

## Security

Report security vulnerabilities privately to: security@telescopeerp.com

Do not disclose vulnerabilities publicly until a fix is available.

---

<div align="center">

Telescope ERP &copy; 2026. All rights reserved.

</div>
