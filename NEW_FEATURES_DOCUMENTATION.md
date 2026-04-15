# New E-Commerce Features Documentation

## Overview
This document outlines all the new features added to the e-commerce platform to ensure a robust, feature-complete shopping experience.

---

## 1. Product Reviews & Ratings

### Models
- **Review** - Stores product reviews with ratings

### Features
- Customers can leave 1-5 star ratings
- Reviews include title and detailed comments
- Verified purchase tracking
- Update or delete own reviews

### Routes (Customer)
```
POST   /customer/reviews/{productId}          - Submit review
DELETE /customer/reviews/{reviewId}           - Delete review
```

### Usage
```php
// Store a review
POST /customer/reviews/5
{
    "rating": 5,
    "title": "Excellent Product",
    "comment": "Very satisfied with quality"
}

// Delete a review
DELETE /customer/reviews/10
```

---

## 2. Product Stock/Inventory Management

### Models
- **Stock** - Manages product inventory with reserved quantity tracking

### Features
- Track available and reserved quantities
- Reserve stock for items in cart
- Automatic low stock alerts
- Restocking history
- Min stock level configuration

### Services
- **StockService** - Business logic for inventory operations

### Key Methods
```php
// Check stock availability
$stockService->hasStock($productId, $quantity);

// Reserve stock for cart
$stockService->reserve($productId, $quantity);

// Deduct confirmed order stock
$stockService->deduct($productId, $quantity);

// Restore stock on cancel
$stockService->restore($productId, $quantity);

// Get stock status
$stockService->getStatus($productId);
```

### Database
- `stocks` table with columns:
  - `product_id` (unique)
  - `quantity` (total stock)
  - `reserved_quantity` (in carts/pending)
  - `min_stock_level` (alert threshold)
  - `last_restocked_at`

---

## 3. Payment Transaction History

### Models
- **Payment** - Comprehensive payment records with gateway responses

### Features
- Multiple payment method support (Razorpay, UPI, Card, NetBanking, etc.)
- Transaction ID tracking
- Payment status management (pending, processing, completed, failed, refunded)
- Gateway response storage
- Timestamp tracking (paid_at)

### Services
- **PaymentService** - Payment operations

### Key Methods
```php
// Create payment record
$paymentService->create($orderId, $amount, $method, $transactionId, $data);

// Mark as completed
$paymentService->markCompleted($paymentId);

// Mark as failed
$paymentService->markFailed($paymentId, $reason);

// Refund payment
$paymentService->refund($paymentId, $amount);
```

### Database
- `payments` table with columns:
  - `order_id`
  - `amount`, `currency`
  - `payment_method`
  - `transaction_id` (unique)
  - `status` (enum)
  - `gateway_response` (JSON)

---

## 4. Return & Refund System

### Models
- **ProductReturn** - Return requests with lifecycle tracking (note: uses `ProductReturn` instead of `Return` due to PHP reserved keywords)
- **ReturnItem** - Individual items being returned

### Features
- Request returns from orders
- Multi-status workflow (requested → approved → shipped → received → completed)
- Refund amount calculation
- Return item tracking
- Rejection reason recording
- Automatic notifications

### Services
- **RefundService** - Return & refund operations

### Key Methods
```php
// Create return request
$refundService->createReturn($orderId, $reason, $items);

// Approve return
$refundService->approveReturn($returnId, $refundAmount);

// Reject return
$refundService->rejectReturn($returnId, $reason);

// Complete return
$refundService->completeReturn($returnId);
```

### Database
- `returns` table
- `return_items` pivot table

### Routes (Future Implementation)
```
GET    /customer/returns                      - List returns
POST   /customer/returns                      - Create return
GET    /customer/returns/{returnId}           - Return details
PATCH  /customer/returns/{returnId}/cancel    - Cancel return
```

---

## 5. Coupon & Discount System

### Models
- **Coupon** - Discount codes with flexible configuration

### Features
- Percentage or fixed amount discounts
- Minimum order amount requirement
- Maximum discount cap
- Usage limit tracking
- Validity date range
- Active/inactive status

### Services
- **CouponService** - Coupon validation and application

### Key Methods
```php
// Validate coupon
$couponService->validate($couponCode, $orderAmount);

// Calculate discount
$couponService->calculateDiscount($couponCode, $orderAmount);

// Apply coupon (increment usage)
$couponService->apply($couponCode);

// Revert coupon (decrement usage)
$couponService->revert($couponCode);
```

### Routes (Vendor/Admin)
```
GET    /vendor/coupons                       - List coupons
GET    /vendor/coupons/create                - Create form
POST   /vendor/coupons                       - Store coupon
GET    /vendor/coupons/{couponId}/edit       - Edit form
PUT    /vendor/coupons/{couponId}            - Update coupon
DELETE /vendor/coupons/{couponId}            - Delete coupon
```

### Database
- `coupons` table with columns:
  - `code` (unique)
  - `discount_type` (percentage/fixed)
  - `discount_value`
  - `min_order_amount`
  - `max_discount_amount`
  - `usage_limit`, `used_count`
  - `valid_from`, `valid_until`
  - `is_active`

---

## 6. Customer Support Ticket System

### Models
- **SupportTicket** - Customer support requests
- **TicketReply** - Ticket responses from customers and admins

### Features
- Create support tickets for orders or general inquiries
- Ticket priority levels (low, medium, high, urgent)
- Multiple status workflow (open → in_progress → waiting_customer → resolved → closed)
- Admin and customer replies
- Thread-based conversations
- Attachment capability (future)

### Services
- None yet (uses controller logic)

### Routes (Customer)
```
GET    /customer/support                     - List tickets
GET    /customer/support/create              - Create form
POST   /customer/support                     - Store ticket
GET    /customer/support/{ticketId}          - Ticket details
POST   /customer/support/{ticketId}/reply    - Add reply
PATCH  /customer/support/{ticketId}/close    - Close ticket
```

### Database
- `support_tickets` table
- `ticket_replies` table

---

## 7. Persistent Notification System

### Models
- **Notification** - User notifications with read tracking

### Features
- Order status notification
- Payment confirmations
- Return/refund updates
- Promotional notifications
- Read/unread tracking
- Flexible data storage (JSON)
- Timestamp tracking

### Routes (Customer)
```
GET    /customer/notifications               - All notifications
PATCH  /customer/notifications/{id}/read     - Mark as read
PATCH  /customer/notifications/read-all      - Mark all as read
DELETE /customer/notifications/{id}          - Delete notification
GET    /customer/notifications/unread        - Get unread count
```

### Key Methods
```php
// Mark as read
$notification->markAsRead();

// Check if read
$notification->isRead();
```

### Database
- `notifications` table with columns:
  - `user_id`
  - `type` (order_placed, payment_received, etc.)
  - `title`, `message`
  - `data` (JSON)
  - `read_at`

### Example Usage
```php
// Create order notification
Notification::create([
    'user_id' => $order->user_id,
    'type' => 'order_placed',
    'title' => 'Order Placed Successfully',
    'message' => "Order #{$order->order_number} has been placed",
    'data' => ['order_id' => $order->id],
]);
```

---

## 8. Database-Backed Cart (Persistent Cart)

### Models
- **CartItem** - User cart items with pricing snapshot

### Features
- Persistent cart for logged-in users
- Price snapshot at add time
- Quantity tracking
- Automatic cleanup on order
- Unique product per user constraint
- Fast checkout integration

### Routes (Customer)
```
GET    /customer/cart                        - View cart
POST   /customer/cart/add/{productId}        - Add to cart
POST   /customer/cart/update/{productId}     - Update quantity
DELETE /customer/cart/remove/{productId}     - Remove item
```

### Database
- `cart_items` table with columns:
  - `user_id` (FK)
  - `product_id` (FK)
  - `quantity`
  - `price` (snapshot)
  - Unique constraint on (user_id, product_id)

### Relationship
```php
// User has many cart items
$user->cartItems();

// Product has many cart items
$product->cartItems();
```

---

## Running Migrations

To set up all new tables, run:

```bash
php artisan migrate
```

This will execute all migrations in order:
1. 2026_04_14_000001_create_reviews_table.php
2. 2026_04_14_000002_create_stocks_table.php
3. 2026_04_14_000003_create_payments_table.php
4. 2026_04_14_000004_create_returns_table.php
5. 2026_04_14_000005_create_return_items_table.php
6. 2026_04_14_000006_create_coupons_table.php
7. 2026_04_14_000007_create_support_tickets_table.php
8. 2026_04_14_000008_create_ticket_replies_table.php
9. 2026_04_14_000009_create_notifications_table.php
10. 2026_04_14_000010_create_cart_items_table.php

---

## Service Integration

### Using Services in Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use App\Services\CouponService;
use App\Services\PaymentService;
use App\Services\RefundService;

class OrderController extends Controller
{
    public function __construct(
        private StockService $stockService,
        private CouponService $couponService,
        private PaymentService $paymentService,
        private RefundService $refundService
    ) {}

    public function placeOrder(Request $request)
    {
        // Check stock
        foreach ($request->items as $item) {
            if (!$this->stockService->hasStock($item['product_id'], $item['qty'])) {
                return back()->with('error', 'Out of stock');
            }
        }

        // Validate and apply coupon
        if ($request->coupon_code) {
            $discount = $this->couponService->calculateDiscount(
                $request->coupon_code,
                $request->subtotal
            );
            $this->couponService->apply($request->coupon_code);
        }

        // Create order
        // ... order creation logic ...

        // Deduct stock
        foreach ($request->items as $item) {
            $this->stockService->deduct($item['product_id'], $item['qty']);
        }

        return redirect()->route('order.success');
    }
}
```

---

## Future Enhancements

1. **Analytics Dashboard** - Sales, returns, and revenue analytics
2. **Bulk Return Processing** - Handle multiple returns at once
3. **Automated Notifications** - Email/SMS on status changes
4. **Return Shipping** - Generate return labels and track shipments
5. **Review Moderation** - Admin approval for reviews
6. **Attachment Support** - File uploads in support tickets
7. **Notification Preferences** - User control over notification types
8. **Stock Alerts** - Admin notifications for low stock
9. **Coupon Analytics** - Track coupon usage and effectiveness
10. **Refund Automation** - Auto-refund after return completion

---

## Testing

All new models include relationships and business logic. Create tests for:

```php
// Test stock availability
$this->assertTrue($stockService->hasStock(1, 5));

// Test coupon validation
$result = $couponService->validate('SAVE10', 1000);
$this->assertTrue($result['valid']);

// Test refund workflow
$return = $refundService->createReturn(1, 'Defective item');
$this->assertEquals('requested', $return->status);
```

---

## Notes

- All timestamps include `created_at` and `updated_at`
- Database uses cascading deletes where appropriate
- Services handle business logic and validation
- Controllers remain thin, delegating to services
- Models include proper relationships and casts
