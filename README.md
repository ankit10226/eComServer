# Laravel Backend – E-commerce API

This is the backend for the E-commerce Web Application, built using **Laravel**. It handles user authentication, product and order management, file uploads, and more.

---

## Requirements
- PHP 8.2.12 (via XAMPP 8.2.12 recommended)
- Composer
- MySQL (via XAMPP) 

## Setup Instructions
Follow these steps to set up the backend locally:

### 1. Install XAMPP 8.2.12
Download from: https://www.apachefriends.org/
Make sure **Apache** and **MySQL** are running in the XAMPP Control Panel.

### 2. Clone the Repository into `htdocs`

### 3. Create Database
Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and create a new database:

Database Name: ecom


### 4. Install PHP Packages
In the project root, run: composer update

### 6. Run Migrations & Seeders
Execute the following to create tables and insert initial data: php artisan migrate --seed

This creates all necessary tables and seeds the **admin user**.

**Admin Credentials:**
Email: admin@gmail.com
Password: 123456

### 7. Create Storage Symlink
This allows uploaded images to be publicly accessible: php artisan storage:link


### 8. Start Laravel Server on `localhost`
Run: php artisan serve --host=localhost
The backend API will now be accessible at:http://localhost:8000

This is required to ensure image/file uploads work correctly when accessed from the frontend.

---

You are now ready to use the Laravel API with your React frontend!













