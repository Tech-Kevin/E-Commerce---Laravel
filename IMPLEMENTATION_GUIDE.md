# Implementation Guide - New E-Commerce Features

## What Was Added

### 10 New Models
1. **Review** - Product reviews with 1-5 star ratings
2. **Stock** - Inventory management with reserved quantity tracking
3. **Payment** - Payment transaction history
4. **ProductReturn** - Return & refund requests (renamed from `Return` to avoid PHP reserved keyword)
5. **ReturnItem** - Individual items in a return
6. **Coupon** - Discount codes and promotions
7. **SupportTicket** - Customer support tickets
8. **TicketReply** - Support ticket conversations
9. **Notification** - User notifications (persistent)
10. **CartItem** - Database-backed persistent shopping cart

### 10 Database Migrations
All migrations are prefixed with `2026_04_14_000*` and will be run with `php artisan migrate`

### 4 Service Classes
1. **StockService** - Inventory operations (reserve, deduct, restore)
2. **CouponService** - Discount validation and calculation
3. **PaymentService** - Payment record management
4. **RefundService** - Return and refund workflow

### 4 Controllers
1. **ReviewController** - Review submission and deletion
2. **SupportTicketController** - Support ticket management
3. **NotificationController** - User notifications
4. **CouponController** - Coupon admin management

### 20+ New Routes
- Customer: Reviews, Support Tickets, Notifications
- Vendor/Admin: Coupon management

### Updated Models With Relationships
- **Product**: Added reviews() and stock() relationships
- **Order**: Added payment() and return() relationships
- **User**: Added reviews(), notifications(), supportTickets(), cartItems(), ticketReplies() relationships

### Utility Files
- **EcommerceHelper.php** - 20+ helper functions for common operations

---

## Quick Setup

### 1. Register Service Classes (if not auto-discovered)
Add to `config/app.php` or use auto-discovery in Laravel 11:

```php
// Already auto-discovered via PSR-4
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Use Services in Controllers
```php
use App\Services\StockService;

class CartController extends Controller
{
    public function __construct(private StockService $stock) {}
    
    public function addToCart($productId)
    {
        if ($this->stock->hasStock($productId)) {
            $this->stock->reserve($productId, 1);
            // Add to cart...
        }
    }
}
```

---

## Key Features by Module

### Stock Management
```php
$stock = stockService();

// Check availability
$stock->hasStock($productId, $qty);

// Reserve for cart
$stock->reserve($productId, $qty);

// Confirm order
$stock->deduct($productId, $qty);

// Cancel order
$stock->restore($productId, $qty);

// Get status
$stock->getStatus($productId);
```

### Coupons
```php
$coupon = couponService();

// Validate
$coupon->validate('SAVE10', 500);

// Calculate discount
$discount = $coupon->calculateDiscount('SAVE10', 500);

// Use coupon
$coupon->apply('SAVE10');

// Revert if needed
$coupon->revert('SAVE10');
```

### Payments
```php
$payment = paymentService();

// Create payment record
$payment->create($orderId, $amount, 'razorpay', $txnId);

// Mark completed
$payment->markCompleted($paymentId);

// Mark failed
$payment->markFailed($paymentId, 'Declined');

// Refund
$payment->refund($paymentId);
```

### Returns/Refunds
```php
$refund = refundService();

// Create return
$refund->createReturn($orderId, 'Defective', [1 => 1]);

// Approve
$refund->approveReturn($returnId, 500);

// Process
$refund->completeReturn($returnId);
```

### Notifications
```php
// Create
Notification::create([
    'user_id' => $userId,
    'type' => 'order_placed',
    'title' => 'Order Placed',
    'message' => 'Your order has been placed',
    'data' => ['order_id' => 1],
]);

// Mark as read
$notification->markAsRead();

// Get unread count
$user->notifications()->whereNull('read_at')->count();
```

---

## Database Schema Overview

### Reviews Table
- id, user_id, product_id, rating (1-5), title, comment, verified_purchase, timestamps

### Stock Table
- id, product_id, quantity, reserved_quantity, min_stock_level, last_restocked_at, timestamps

### Payments Table
- id, order_id, amount, currency, payment_method, transaction_id, status, paid_at, gateway_response, timestamps

### Returns Table
- id, order_id, reason, status, refund_amount, requested_at, approved_at, completed_at, rejection_reason, timestamps

### Coupons Table
- id, code, description, discount_type, discount_value, min_order_amount, max_discount_amount, usage_limit, used_count, valid_from, valid_until, is_active, timestamps

### Support Tickets Table
- id, user_id, order_id, subject, description, status, priority, resolved_at, timestamps

### Notifications Table
- id, user_id, type, title, message, data (JSON), read_at, timestamps

### Cart Items Table
- id, user_id, product_id, quantity, price, timestamps (with unique constraint on user_id, product_id)

---

## Enums/Status Values

### Payment Status
- pending, processing, completed, failed, refunded

### Return Status
- requested, approved, rejected, shipped, received, completed

### Support Ticket Status
- open, in_progress, waiting_customer, resolved, closed

### Support Ticket Priority
- low, medium, high, urgent

### Coupon Discount Type
- percentage, fixed

---

## Routes Summary

### Customer Routes (/customer/)
```
POST   /reviews/{productId}           - Submit review  
DELETE /reviews/{reviewId}            - Delete review
GET    /support                       - List tickets
POST   /support                       - Create ticket
GET    /support/{id}                  - View ticket
POST   /support/{id}/reply            - Reply to ticket
PATCH  /support/{id}/close            - Close ticket
GET    /notifications                 - List notifications
PATCH  /notifications/{id}/read       - Mark read
PATCH  /notifications/read-all        - Mark all read
DELETE /notifications/{id}            - Delete notification
```

### Vendor Routes (/vendor/)
```
GET    /coupons                       - List coupons
POST   /coupons                       - Create coupon
PUT    /coupons/{id}                  - Update coupon
DELETE /coupons/{id}                  - Delete coupon
```

---

## Helper Functions

```php
// Ratings
getProductRating($productId);           // Get avg rating
getProductReviewCount($productId);      // Get review count

// Stock
isProductInStock($productId);           // Check availability
getAvailableQuantity($productId);       // Get available qty

// Notifications
notifyUser($userId, $type, $title, $msg, $data);
getUnreadNotifications($userId);
notifyOrderStatusChange($orderId, $status);

// Services
stockService();        // Get stock service
couponService();       // Get coupon service
paymentService();      // Get payment service
refundService();       // Get refund service

// Utilities
formatPrice($amount);
canReturnOrder($orderId);
generateOrderNumber();
generateTicketNumber();
```

---

## Integration Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Test models load without errors
- [ ] Update existing controllers to use services
- [ ] Add notification triggers to order events
- [ ] Update CartController to use CartItem model
- [ ] Create admin views for coupon management
- [ ] Create customer views for reviews
- [ ] Create customer views for support
- [ ] Create customer views for notifications
- [ ] Update checkout to validate stock
- [ ] Update checkout to apply coupons
- [ ] Add low stock alerts to vendor dashboard
- [ ] Test payment workflow with Payment model
- [ ] Test return workflow with RefundService
- [ ] Add notification preferences to customer profile

---

## Important Notes

1. **Auto-discovery**: Services use Laravel's auto-discovery, so they're automatically registered
2. **Service binding**: Use dependency injection in controllers
3. **Relationships**: All models have proper relationships defined
4. **Migrations**: Run in order (they have timestamps)
5. **Cascading**: Foreign keys have cascading deletes where appropriate
6. **JSON storage**: Payment gateway responses stored as JSON
7. **Indexes**: Add indexes to frequently queried columns if performance needed
8. **Caching**: Consider caching coupon validation for high traffic

---

## Example: Complete Order Flow

```php
// 1. Check stock
if (!stockService()->hasStock($productId, $qty)) {
    abort(404, 'Out of stock');
}

// 2. Reserve stock
stockService()->reserve($productId, $qty);

// 3. Create order
$order = Order::create([...]);

// 4. Create cart items if needed
CartItem::create([
    'user_id' => auth()->id(),
    'product_id' => $productId,
    'quantity' => $qty,
    'price' => $product->price,
]);

// 5. Apply coupon
if ($couponCode) {
    $discount = couponService()->calculateDiscount($couponCode, $subtotal);
    couponService()->apply($couponCode);
}

// 6. Create payment record
paymentService()->create($order->id, $amount, 'razorpay', $txnId);

// 7. Notify user
notifyUser($order->user_id, 'order_placed', 'Order Placed', "Your order #{$order->order_number}");

// 8. Deduct stock
stockService()->deduct($productId, $qty);

// 9. Clear cart
CartItem::where('user_id', auth()->id())->delete();
```

---

## Need Help?

Refer to:
- `NEW_FEATURES_DOCUMENTATION.md` - Detailed feature documentation
- `app/Services/*` - Service implementations
- `app/Models/*` - Model definitions with relationships
- `app/Helpers/EcommerceHelper.php` - Utility functions
