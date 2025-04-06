# Restaurant Management System

A modern, **table-based** restaurant management system designed to streamline order registration, enhance service efficiency, and improve overall restaurant operations. Orders are managed per table, making it easier for staff to track and serve guests efficiently.

---

## 🔧 Tech Stack

- **Frontend:** Vue.js  
- **Backend:** Lumen (Laravel Micro-framework)  
- **Database:** MySQL

---

## 🚀 Features

- Table-based order management  
- Real-time order updates and status tracking  
- Menu and item management  
- Role-based access control (admins, kitchen staff, etc.)  
- Responsive UI for desktops, tablets, and mobile devices  
- Simple and intuitive interface for fast-paced restaurant environments

---

## 📦 Installation Guide

### 🔙 Backend Setup (Lumen)


git clone https://github.com/amaneth404/Restaurant-Management-system-.git
cd Restaurant-Management-system-/backend
composer install
cp .env.example .env
# Open the .env file and configure DB credentials

php artisan migrate
php artisan key:generate
php -S localhost:8000 -t public


### Frontend (Vue.js)
cd Restaurant-Management-system-/frontend
npm install
npm run serve
