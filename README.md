# emmar-orders

Laravel 9 app that imports orders from Foodics, stores them locally, and sends daily/monthly sales aggregates to Emaar.

## Requirements
- PHP 8.0+
- Composer
- MySQL/MariaDB
- Queue worker (database driver recommended)

## Setup
1) Copy env and install deps
```
cp .env.example .env
composer install
php artisan key:generate
```
2) Configure database in `.env`, then migrate
```
php artisan migrate
```
3) Seed reference data
```
php artisan db:seed --class=BranchSeeder
php artisan db:seed --class=PaymentMethodSeeder
```

## Environment variables
- LIST_ORDERS_BEARER_TOKEN: Foodics API bearer token
- EMAAR_DAILY_API_URL: Emaar daily sales endpoint
- EMAAR_XAPIKEY: Emaar API key
- QUEUE_CONNECTION: set to `database` for queued jobs

## Queue and jobs
- Orders are fetched from Foodics per branch via queued `ListOrders` jobs.
- Daily summary is sent with `daily:orders`.
- Monthly summary is sent with `monthly:orders`.
- Use database queue: `php artisan queue:table && php artisan migrate`, then `php artisan queue:work`.

## Manual commands
- Fetch yesterday’s Foodics orders: `php artisan foodics:orders`
- Send daily sales to Emaar: `php artisan daily:orders`
- Send monthly sales to Emaar: `php artisan monthly:orders`

## Web features
- Authenticated dashboard for orders and logs.
- Orders page: filter by branch, business date, payment method, sent-to-Emaar flag; trigger fetch/queue run.
- Logs page: view API call logs and daily/monthly job results.

## Data model
- `orders`: Foodics orders with tax, net amount, branch, payment method, sent-to-Emaar flag.
- `payment_methods`: mapping of Foodics payment IDs to Emaar channels (`emmar_mapping`, `share_with_emaar`).
- `branches`: branch metadata (unit no, lease code).

## Testing
```
php artisan test
```
