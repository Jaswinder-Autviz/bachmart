# BachatMart 🛒

A modern, high-performance eCommerce marketplace platform built with **Laravel**, featuring clean Shopify-grade UI/UX, multi-seller catalog management, category navigation, and direct WhatsApp order inquiries.

---

## ✨ Features

- **🛍️ Shopify-Grade Minimal Storefront**: Clean, modern product cards with hover effects, discount badges, and smooth layout.
- **🏷️ Category Navigation**: Effortless horizontal category browsing with seamless touch & drag scrolling.
- **📱 Direct WhatsApp Checkout/Inquiry**: Quick action button for instant customer-to-seller communication and ordering.
- **🔍 Fast Search & Filtering**: Real-time product search and category filtering.
- **🏪 Multi-Seller & Admin Portals**: Dedicated seller and administrator panels for product management, inventory tracking, categories, and featured collections.
- **⚡ Responsive & Mobile-First**: Crafted with vanilla CSS and responsive typography for ultra-fast loading and cross-device compatibility.

---

## 🛠️ Tech Stack

- **Backend Framework**: Laravel 10.x / 11.x
- **Frontend**: Blade Templates, Vanilla CSS, JavaScript
- **Icons**: Lucide Icons & FontAwesome
- **Database**: MySQL / MariaDB (or SQLite)
- **Asset Bundler**: Vite

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL / MariaDB or SQLite
- Node.js & npm

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Jaswinder-Autviz/bachmart.git
   cd bachmart
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies & build assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in `.env`.*

5. **Run database migrations & seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Create storage link:**
   ```bash
   php artisan storage:link
   ```

7. **Start the local server:**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
