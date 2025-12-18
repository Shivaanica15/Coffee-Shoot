# CoffeeShoot – Admin Module

CoffeeShoot is a PHP/MySQL web application that connects clients with photography studios.  
This document focuses on the **Admin Panel**, explaining its purpose, capabilities, files, and data model.

---

## 🔐 Admin Panel Overview

- **Access Control:**  
  Admin access is role-based. After login, the system checks `registered_user.type = 'admin'`.  
  Non-admin users are redirected to `login_signup.php`.

- **Scope:**  
  The admin panel provides centralized control over:
  - User management
  - Booking monitoring
  - Inquiry/message viewing

- **Entry Points:**
  - Login: `http://localhost/Cofeeshoot/login_signup.php`
  - Admin Dashboard: `admin/admin home.html`
  - User Management: `admin.php`
  - Bookings: `admin/viewallbooking.php`
  - Inquiries: `admin/viewallinquire.php`
  - Logout: `admin/logout.php`

---

## 🧑‍💼 Admin Capabilities (`admin.php`)

### User Management
- **View users:**  
  Displays all records from `registered_user`.

- **Create user:**  
  - Username  
  - Email  
  - Phone  
  - Role (`admin`, `client`, `studio`)  
  - Studio name (if applicable)  
  - Password securely hashed using `password_hash()`  

- **Update user:**  
  - Edit username, phone, studio name, and role  
  - Reset password (re-hashed on update)

- **Delete user:**  
  - Remove user by email (confirmation required)

### Navigation
- Centralized admin dashboard with links to:
  - Users
  - Bookings
  - Inquiries
  - Admin contact messages
  - Logout

### Session Enforcement
- Admin pages require:
  - Active session
  - `type = 'admin'`
- Unauthorized access redirects to login.

---

## 🗄️ Data Model (Admin-Related Tables)

### `registered_user`
| Column | Description |
|------|------------|
| Username | Display name |
| password | Bcrypt-hashed password |
| phone | Contact number |
| mobileNo | Legacy compatibility |
| email | Unique identifier |
| type | Role (admin/client/studio) |
| studio_name | Studio name (if studio role) |
| profile_picture | User image |

### `booking` / `bookings`
- Stores all client booking data
- Read-only for admins

### `Inquiries`
- Stores messages submitted via contact forms
- Viewable by admins

Seed data is provided in `database/mydb.sql`.

---

## 🚀 Getting Started (Local XAMPP)

1. Start **Apache** and **MySQL** in XAMPP.
2. Place the project in:
C:\xampp\htdocs\Cofeeshoot

markdown
Copy code
3. Create and seed the database:
- phpMyAdmin:
  - Create database `mydb`
  - Import `database/mydb.sql`
- OR via CLI:
  ```bash
  mysql -u root -p < database/mydb.sql
  ```
4. Database credentials (default):
- Host: `localhost`
- User: `root`
- Password: *(empty)*
- Database: `mydb`
- Files: `config.php`, `config2.php`

5. Ensure upload directories are writable:
- `uploads/`
- `uploads/profile_pictures/`

6. Log in and access admin panel:
- `login_signup.php`
- After admin login, open `admin/admin home.html` or `admin.php`

---

## 🔑 Seeded Admin Account

- **Email:** `admin@coffeeshoot.local`
- **Role:** `admin`
- **Password:** bcrypt-hashed in seed file

If the password is unknown:
password_hash('newpassword', PASSWORD_DEFAULT)
Update the registered_user.password field using phpMyAdmin or SQL.

---

📂 Admin-Related Files
admin.php – main admin user management

admin/admin home.html – admin dashboard

admin/viewallusers.php – user list

admin/viewallbooking.php – bookings list

admin/viewallinquire.php – inquiries list

admin/admin contact us.php – admin messages

admin/logout.php – session termination

config2.php – admin DB connection

database/mydb.sql – schema and seed data

---

🔐 Security Notes
Passwords are hashed using password_hash() and verified with password_verify()

Admin access is enforced using session and role checks

Prepared statements are used for admin CRUD operations

For production use:

Change database credentials

Add CSRF protection

Restrict access to upload directories

---

🧪 Troubleshooting (Admin)
Redirected to login: user not logged in or not an admin

Cannot log in: reset password using bcrypt hash

DB connection error: ensure MySQL is running and credentials are correct

Changes not saved: ensure required fields are provided and email is unique

---

📚 Academic Note
This Admin Module was developed for educational purposes as part of a First Year – Second Semester university project, demonstrating role-based access control, CRUD operations, and basic web application security practices.

---

📄 License
This project is intended for academic use only.
