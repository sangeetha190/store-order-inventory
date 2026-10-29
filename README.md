# Store Order & Inventory Management System

A Laravel REST API application developed as part of the Mallow Technologies Laravel Developer take-home assignment.

## Features

- Product catalog and inventory management
- Customer management with unique email addresses
- Order creation with multiple product items
- Automatic subtotal, tax, and grand-total calculation
- Stock validation and inventory deduction
- Customer order history by email
- Configurable low-stock reporting
- Queued order-confirmation job
- Automated Laravel feature tests

## Technology Stack

- PHP
- Laravel
- MySQL
- Eloquent ORM
- Laravel Queues
- PHPUnit

## Installation and Setup

### 1. Clone the repository

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd store-order-inventory
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your database credentials in `.env` and create the database.

### 4. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 5. Start the Laravel server

```bash
php artisan serve
```

## Running Tests

Run all tests:

```bash
php artisan test
```

Run the order API feature tests:

```bash
php artisan test --filter=OrderApiTest
```

## API Endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/orders` | Create an order |
| GET | `/api/customers/order-history?email=customer@example.com` | Retrieve customer order history |
| GET | `/api/products/low-stock?threshold=10` | Retrieve low-stock products |

## Design Decisions and Assumptions

- Customer email addresses uniquely identify customer records.
- Orders can contain multiple product items.
- Order totals are calculated by the backend.
- Insufficient stock should prevent order creation.
- Order creation and stock deduction should be protected by database transactions and concurrency-safe stock updates.
- Order confirmation is simulated using a queued job without requiring real SMTP credentials.

Verify that these features, commands, and endpoints match the actual implementation before submission.
