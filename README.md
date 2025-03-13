# iSagha E-Commerce Task

This is a Laravel-based e-commerce application that includes authentication, shopping cart functionality, and payment integration using Paymob.

## Requirements
- **PHP**: ^8.2
- **Composer**: Latest version
- **MySQL**: 8.0+
- **Laravel**: 12+
- **Node.js & NPM** (if using frontend assets like Vue or React)

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Omar-AAyman/isagha-ecommerce-task.git
cd isagha-ecommerce-task
```

### 2. Install Dependencies
```bash
composer install
npm install  # If using frontend assets
```

### 3. Environment Configuration
Copy the example environment file and update the required settings:
```bash
cp .env.example .env
```
Update the `.env` file with your database credentials, Paymob API keys, and social authentication settings.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate --seed
```
This will create necessary database tables and seed the database with sample products.

### 6. Run the Application
```bash
php artisan serve
```

## Authentication

### Google Login
- **Local Machine**: `http://127.0.0.1:8000/auth/google/callback`
- **Server**: `https://isagha-ecommerce.kesug.com/auth/google/callback`

> Facebook login is only available on the production server due to HTTPS restrictions.

## Payment Integration
- **Paymob Webhook URL**: `https://isagha-ecommerce.kesug.com/payment/webhook`
- The system automatically handles Paymob redirections after payment.

## Additional Commands

To clear and cache configurations:
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

To run the queue worker (if needed):
```bash
php artisan queue:work
```

## Deployment Notes
Ensure the following are set up correctly in production:
- `.env` file with correct credentials
- SSL certificate for secure authentication (Facebook login requires HTTPS)
- Queues and cron jobs for background tasks

For any issues, check the Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

---
This README provides a summarized and clear guide to setting up and running the project. 🚀

