# Deployment Guide — Manufacturing Company

## Overview

Panduan lengkap deploy Telescope ERP untuk client manufacturing company.

---

## Step 1: Server Requirements

```
- PHP 8.3+
- MySQL 8.0+ atau SQLite 3.8.3+
- Composer 2.0+
- Node.js 18+
```

---

## Step 2: Clone & Install

```bash
# Clone repo
git clone https://github.com/akufikri/telescope-erp.git
cd telescope-erp

# Install dependencies
composer install
npm install && npm run build

# Setup env
cp .env.example .env
php artisan key:generate
```

---

## Step 3: Konfigurasi .env

Edit `.env` untuk manufacturing company:

```env
APP_NAME="Manufacturing ERP"
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manufacturing_erp
DB_USERNAME=root
DB_PASSWORD=

# Module filtering — hanya manufacturing modules
ACTIVE_MODULES=manufacturing,inventory,employee,project,setting,help

# Currency
APP_CURRENCY=IDR
```

### Penjelasan ACTIVE_MODULES

| Module | Fungsi |
|--------|--------|
| `manufacturing` | BOM, Manufacturing Orders, Work Orders, Work Centers |
| `inventory` | Stock management, warehouse, receipts, deliveries |
| `employee` | Employee profiles, departments, org structure |
| `project` | Project planning, tasks, timesheets |
| `setting` | System configuration, users, roles |
| `help` | User guides dan documentation |

---

## Step 4: Database Setup

```bash
# Jalankan migrations & seeders
php artisan erp:install
```

**Yang dilakukan:**
- Membuat semua tabel database
- Seed data awal (currencies, countries, etc)
- Generate roles & permissions (Filament Shield)
- Buat admin account

---

## Step 5: Akses Admin Panel

```
URL: http://your-domain.com/admin
```

**Login pertama kali:**
- Email: (dari hasil erp:install)
- Password: (dari hasil erp:install)

**Change password setelah login pertama.**

---

## Step 6: Setup Company

1. Login ke admin panel
2. Buka **Settings** → **Companies**
3. Create company baru:
   - Company Name: [Nama Perusahaan]
   - Currency: IDR
4. Assign user ke company

---

## Step 7: Setup Users & Roles

### Buat User untuk Client

1. Buka **Settings** → **Users**
2. Click **Add User**
3. Fill data:
   - Name
   - Email
   - Password
4. Assign role:
   - **Admin** — full access
   - **Manager** — view & edit
   - **Staff** — view only

### Role Permissions

| Role | Access |
|------|--------|
| Admin | Full access semua modules |
| Manufacturing Manager | Manufacturing + Inventory |
| HR Manager | Employee + Project |
| Staff | View only sesuai assignment |

---

## Step 8: Manufacturing Setup

### 8.1 Work Centers

1. Buka **Manufacturing** → **Configurations** → **Work Centers**
2. Create work centers:
   - Assembly Line
   - Quality Check
   - Packaging
   - dll

### 8.2 Operations

1. Buka **Manufacturing** → **Configurations** → **Operations**
2. Create operations:
   - Cutting
   - Assembly
   - Finishing
   - Quality Control

### 8.3 Bill of Materials (BOM)

1. Buka **Manufacturing** → **Products** → **Bill of Materials**
2. Create BOM:
   - Product: [nama product]
   - Components: [raw materials]
   - Operations: [work steps]

---

## Step 9: Inventory Setup

### 9.1 Warehouses

1. Buka **Inventory** → **Configurations** → **Warehouses**
2. Create warehouses:
   - Main Warehouse
   - Raw Material Storage
   - Finished Goods

### 9.2 Locations

1. Buka **Inventory** → **Configurations** → **Locations**
2. Create locations dalam warehouse:
   - Shelf A1
   - Shelf A2
   - Loading Dock

---

## Step 10: Employee Setup

### 10.1 Departments

1. Buka **Employees** → **Configurations** → **Departments**
2. Create departments:
   - Production
   - Quality Assurance
   - Warehouse
   - HR

### 10.2 Positions

1. Buka **Employees** → **Configurations** → **Positions**
2. Create positions:
   - Production Manager
   - Line Supervisor
   - Machine Operator
   - QC Inspector

### 10.3 Add Employees

1. Buka **Employees** → **Employees**
2. Click **Add Employee**
3. Fill data:
   - Personal info
   - Department
   - Position
   - Employment contract

---

## Step 11: Access User Guide

User bisa akses help guides:

```
URL: http://your-domain.com/admin/help
```

**Guide tersedia:**
- Manufacturing — step-by-step usage
- Inventory — stock management
- Employees — HR management
- Project — task management
- Settings — configuration

---

## Step 20: Monitoring

### Dashboard

Admin bisa monitor:
- Manufacturing Orders status
- Inventory levels
- Employee attendance
- Project progress

### Reports

Available reports:
- Manufacturing efficiency
- Inventory valuation
- Employee productivity
- Project budget vs actual

---

## Troubleshooting

### Module tidak tampil

```
php artisan config:clear
php artisan cache:clear
```

### Website redirect ke admin login

Ini normal. Website module OFF, semua akses ke `/` redirect ke `/admin/login`.

### Database error

```
php artisan migrate:fresh --seed
php artisan erp:install
```

---

## Checklist Deployment

- [ ] Server requirements terpenuhi
- [ ] Code deployed
- [ ] .env configured
- [ ] Database migrated
- [ ] Admin account created
- [ ] Company setup
- [ ] Users & roles configured
- [ ] Work centers defined
- [ ] Warehouses created
- [ ] Employees added
- [ ] BOM created
- [ ] User guide accessible
