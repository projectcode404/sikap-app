# Laravel Project

This is a Laravel 12 project for managing vehicles, ATK, and employees.

## Installation

1. Clone the repository:
   git clone https://github.com/projectcode404/sikap-app.git

2. Install dependencies:
   composer install
   npm install && npm run build

3. Copy the .env file and configure database:
   cp .env.example .env
   php artisan key:generate

4. Docker
   docker-compose up -d --build
   docker exec -it sikap-app sh
   php artisan serve --host=0.0.0.0 --port=8080
   browser http://localhost:8001