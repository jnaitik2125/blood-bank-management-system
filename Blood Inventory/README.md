# Blood Donation and Inventory Management System (Core PHP + MySQL)

Lightweight MVC web app using pure PHP (no framework), PDO, role-based auth, CSRF protection, and blood inventory automation.

## 1) Folder Structure

```
/
|-- app/
|   |-- controllers/
|   |-- models/
|   `-- views/
|-- core/
|-- config/
|-- public/
|   |-- assets/
|   |-- .htaccess
|   `-- index.php
|-- routes/
|-- .htaccess
|-- database.sql
`-- README.md
```

## 2) Requirements

- PHP 8.1+ with `pdo_mysql`
- MySQL 8+ (or MariaDB compatible)
- Apache with `mod_rewrite` enabled (XAMPP recommended)

## 3) Setup Steps (XAMPP / localhost)

1. Copy project folder to `htdocs`, for example:
   - `C:/xampp/htdocs/Blood Inventory`
2. Create DB and import schema:
   - Open phpMyAdmin
   - Create DB name: `blood_inventory` (or use SQL auto-create)
   - Import `database.sql`
3. Configure DB credentials in `config/config.php`:
   - `host`, `port`, `name`, `user`, `pass`
4. Start Apache + MySQL in XAMPP.
5. Open URL:
   - `http://localhost/Blood%20Inventory/public/`

If hosted differently, set `base_path` in `config/config.php` to your public URL prefix.

## 4) Default Login Accounts

- Admin: `admin@blood.local` / `password123`
- Receptionist: `reception@blood.local` / `password123`
- Collection Staff: `collector@blood.local` / `password123`

## 5) Security Implemented

- Password hashing with `password_hash()`
- Session-based authentication
- Role-based access control per route
- CSRF token validation for mutating requests
- SQL injection prevention via prepared statements (PDO)
- XSS mitigation using output escaping helper `e()`

## 6) Core Features

- User management (CRUD, Admin only)
- Donor management (CRUD)
- Donation entry (auto inventory update + log)
- Inventory management (CRUD + expiry auto-marking)
- Request management (CRUD + admin approval/rejection)
- Auto stock deduction on approved requests
- Basic report tables (donations, requests, inventory logs)
- Search/filter for donors, inventory, requests
- Pagination for major listings (users, donors, donations, inventory, requests)
- Vanilla AJAX submit + list refresh for donors and requests (no external libraries)
- Stricter production validation (email/phone/date/blood group/quantity constraints)

