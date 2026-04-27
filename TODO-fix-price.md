# Fix price undefined array key in order-detail.php

## Steps
- [x] Fix `resources/views/order-detail.php` — add fallback for `$item['price']` and `$item['subtotal']`
- [x] Fix `app/Models/Order.php` — update `getItems()` query to alias `product_price as price`
- [x] Fix `app/Controllers/HomeController.php` — update queries in `orderDetail()` and `orderLookup()` to alias `product_price as price`
- [x] Fix image path in `order-detail.php` (wrong folder `assets/images/image_products/` → correct `storage/uploads/`)
- [x] Add Toast notification + AJAX for status update in `admin/orders/view.php`
- [x] Add payment_status update form in `admin/orders/view.php`
- [x] Add `paymentStatus()` method in `OrderController.php`
- [x] Add route `admin/orders/payment-status` in `public/index.php`
- [x] Fix badge colors in `my-orders.php` (Bootstrap 5 `bg-*` classes)
- [x] Test by refreshing order-detail page

