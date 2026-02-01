# Take-Home Test - Implementation Notes

## Author
- **Name:** Demas Zhafran Zharif
- **Date:** February 1, 2026
- **Repository:** https://github.com/demmm20/takehome-test-ordo.git

---

## Overview

This document describes the changes made to complete the take-home test requirements for the Laravel 10 E-commerce project.

---

## Task 1: Add Order Product Lists on Order Detail

### What Was Changed

Added a comprehensive "Order Products" section to the user order detail page (`/user/order/show/{order_id}`).

### Files Modified

**resources/views/user/order/show.blade.php**
- Added new section "Order Products" with detailed product table
- Displays: product image, name, description, price, quantity, and total
- Shows order summary: subtotal, shipping charge, discount (if applicable), and total amount
- Implemented responsive styling with Bootstrap classes

### Why This Approach

Previously, users could only see general order information but not the specific products purchased. This enhancement provides:
- Clear visibility of all products in the order
- Price and quantity verification for each item
- Improved transparency and user experience

### Evidence

See `screenshots/1-order-detail-with-products.png` for the Order Products section display.

---

## Task 2: Preserve Order Details When Products Are Modified/Deleted

### Problem Identified

When admin updates or deletes a product, order details would show errors or missing information because the system was fetching live data from the `products` table.

### Solution: Product Snapshot Pattern

Implemented a "snapshot" system that captures and preserves product data at the time of order creation. Even if the product is later modified or deleted by admin, the order maintains the original product information.

---

### Database Changes

#### New Migration
**File:** `database/migrations/2026_01_31_184017_fix_carts_table_for_snapshots.php`

Added three new columns to `carts` table:
```php
$table->string('product_title')->nullable();
$table->string('product_photo')->nullable();
$table->text('product_summary')->nullable();
```

**Column purposes:**
- `product_title`: Stores product name at checkout time
- `product_photo`: Stores image URL at checkout time
- `product_summary`: Stores product description at checkout time

All columns are nullable for backward compatibility with existing orders.

**Migration command:**
```bash
php artisan migrate
```

---

### Code Changes

#### 1. Cart Model
**File:** `app/Models/Cart.php`

Added snapshot columns to `$fillable` array:
```php
protected $fillable = [
    'user_id', 'product_id', 'order_id', 'quantity', 
    'amount', 'price', 'status',
    'product_title', 'product_photo', 'product_summary' // New additions
];
```

---

#### 2. OrderController
**File:** `app/Http/Controllers/OrderController.php`

**Changes in `store()` method:**

For COD (Cash on Delivery) orders:
```php
if ($validated['payment_method'] == 'cod') {
    // 1. Link cart items to order first
    Cart::where('user_id', auth()->user()->id)
        ->where('order_id', null)
        ->update(['order_id' => $order->id]);
    
    // 2. Fetch cart items that now have order_id set
    $cartItemsWithOrder = Cart::where('order_id', $order->id)->get();
    
    // 3. Save product snapshot for each cart item
    foreach ($cartItemsWithOrder as $cartItem) {
        $product = $cartItem->product;
        
        if ($product) {
            $cartItem->product_title = $product->title;
            $cartItem->product_photo = $product->photo;
            $cartItem->product_summary = $product->summary;
            $cartItem->save();
        }
    }
    
    // 4. Clear session and send notifications
    session()->forget('cart');
    session()->forget('coupon');
}
```

**Why this order?**
- Must link cart items to order first (set `order_id`)
- Then query cart items by `order_id`
- Then save snapshot data

For PayPal orders:
```php
if ($validated['payment_method'] == 'paypal') {
    // Save snapshot BEFORE payment
    foreach ($cartItems as $cartItem) {
        $product = $cartItem->product;
        
        if ($product) {
            $cartItem->product_title = $product->title;
            $cartItem->product_photo = $product->photo;
            $cartItem->product_summary = $product->summary;
            $cartItem->save();
        }
    }
    
    // Redirect to payment
    return redirect()->route('payment')->with(['id' => $order->id]);
}
```

**Why different from COD?**
- PayPal: Save snapshot before payment (in case product is deleted during payment)
- Cart items only linked to order after successful payment (handled in PaymentController)

---

#### 3. HomeController
**File:** `app/Http/Controllers/HomeController.php`

**Updated `orderShow()` method:**

Before:
```php
public function orderShow($id) {
    $order = Order::find($id);
    return view('user.order.show')->with('order', $order);
}
```

After:
```php
public function orderShow($id) {
    $order = Order::with(['cart_info', 'shipping'])->findOrFail($id);
    return view('user.order.show')->with('order', $order);
}
```

**Benefits:**
- `with(['cart_info', 'shipping'])`: Eager loading prevents N+1 query problems
- `findOrFail()`: Returns 404 if order not found, better than `find()`

---

#### 4. Order Detail Views
**File:** `resources/views/user/order/show.blade.php`

Now uses snapshot data:
```blade
<td>
  <strong>{{$cart->product_title ?? 'Product Not Available'}}</strong>
  @if($cart->product && $cart->product->id)
    <a href="{{route('product-detail', $cart->product->slug)}}" target="_blank">
      View Product
    </a>
  @endif
</td>
```

**Explanation:**
- `$cart->product_title`: Uses snapshot data (not `$cart->product->title`)
- "View Product" link only appears if product still exists
- If product deleted, name still displays from snapshot

---

### How Snapshot System Works

**Scenario 1: Normal Order**
1. User completes checkout
2. System saves snapshot (product name, photo, description)
3. User views order detail → complete data displayed

**Scenario 2: Admin Edits Product**
1. Admin changes product name from "Red Dress" to "Crimson Dress"
2. Admin changes price from $50 to $45
3. User views old order
4. Order still shows "Red Dress" at $50 (from snapshot)
5. Historical data remains accurate

**Scenario 3: Admin Deletes Product**
1. Admin deletes product from database
2. User views old order
3. Order still shows product name, photo, description (from snapshot)
4. "View Product" link is hidden (product no longer exists)
5. No errors, data remains complete

---

### Additional Bug Fixes

**Issue:** Error "Attempt to read property 'price' on null"

**Root Cause:** Some orders have null `shipping_id`, but code attempted to access `$order->shipping->price` without null check.

**Solution:** Added null checks in all views accessing shipping price.

**Files Fixed:**
1. `resources/views/backend/order/show.blade.php` (lines 32, 83)
2. `resources/views/user/order/show.blade.php` (multiple locations)
3. `resources/views/user/order/index.blade.php` (line 52)

From:
```blade
<td>${{$order->shipping->price}}</td>
```

To:
```blade
<td>${{$order->shipping ? number_format($order->shipping->price, 2) : '0.00'}}</td>
```

Now displays $0.00 when shipping is null instead of throwing error.

---

## Testing Results

### Test Case 1: View Order with Products
- Order Products section displays correctly
- Product images, names, and prices shown
- Order summary accurate

### Test Case 2: Admin Edits and delete Product
- Admin changes product name and price
- Admin deletes product from system
- Old order still displays original data
- Order still displays deleted product (from snapshot)
- "View Product" link appropriately hidden
- Data unchanged (see `screenshots/2-admin-edit-product.png`, `screenshots/3-admin-delete-product.png`, and see `screenshots/4-order-after-edit-and-delete.png` )

### Test Case 4: Orders with Null Shipping
- Orders without shipping display $0.00
- No errors on order detail or list pages

---

## Task 3: Project Improvement Suggestions

Below are suggestions to improve the project quality:

### 1. Security: Add Order Authorization

**Problem:**
Users can view other users' orders by guessing the order ID. Example:
- User A creates order ID 1
- User B accesses `/user/order/show/1`
- User B can see User A's order!

**Suggestion:**
Implement authorization using Policy.
```php
// app/Policies/OrderPolicy.php
class OrderPolicy {
    public function view(User $user, Order $order) {
        // User can only view own orders, admin can view all
        return $user->id === $order->user_id || $user->role === 'admin';
    }
}

// In HomeController
public function orderShow($id) {
    $order = Order::with(['cart_info', 'shipping'])->findOrFail($id);
    
    $this->authorize('view', $order); // Throws 403 if unauthorized
    
    return view('user.order.show', compact('order'));
}
```


---

### 2. Database: Add Indexes for Better Performance

**Problem:**
Queries will slow down as order and product data grows due to lack of indexes.

**Suggestion:**
Add indexes on frequently queried columns.
```php
// Migration
Schema::table('orders', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('order_number');
    $table->index('status');
    $table->index('created_at');
});

Schema::table('carts', function (Blueprint $table) {
    $table->index('order_id');
    $table->index('user_id');
});

Schema::table('products', function (Blueprint $table) {
    $table->index('slug');
    $table->index('status');
});
```

**Benefit:** Queries will be 10-100x faster with large datasets.


---

### 3. User Experience: Add Email Notifications

**Problem:**
Users don't receive email confirmation after ordering. They must manually check website for order status.

**Suggestion:**
Send automatic emails when:
- Order successfully created
- Order status changes (processing, shipped, delivered)
```php
// app/Notifications/OrderPlaced.php
class OrderPlaced extends Notification {
    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Order Confirmation - ' . $this->order->order_number)
            ->line('Thank you for your order!')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Total: $' . number_format($this->order->total_amount, 2))
            ->action('View Order', route('user.order.show', $this->order->id));
    }
}

// In OrderController after order creation
$user->notify(new OrderPlaced($order));
```


---

### 4. Code Quality: Extract Business Logic to Service Classes

**Problem:**
`OrderController::store()` method is too long (170+ lines). Contains validation, order creation, payment handling, notifications, and session management in one method.

**Suggestion:**
Move business logic to Service classes.
```php
// app/Services/OrderService.php
class OrderService {
    public function createOrder(array $data, User $user): Order {
        $order = new Order();
        $order->fill($data);
        $order->user_id = $user->id;
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->save();
        
        return $order;
    }
    
    public function saveProductSnapshots(Order $order): void {
        $cartItems = Cart::where('order_id', $order->id)->get();
        
        foreach ($cartItems as $item) {
            if ($product = $item->product) {
                $item->update([
                    'product_title' => $product->title,
                    'product_photo' => $product->photo,
                    'product_summary' => $product->summary
                ]);
            }
        }
    }
}

// In OrderController becomes simpler
public function store(Request $request, OrderService $orderService) {
    $validated = $request->validate([...]);
    
    $order = $orderService->createOrder($validated, auth()->user());
    $orderService->saveProductSnapshots($order);
    
    return redirect()->route('home');
}
```

**Benefit:** Cleaner code, easier to test, reusable logic.


---

### 5. Testing: Add Automated Tests

**Problem:**
No automated tests currently. Every change requires manual testing. High risk of bugs with future changes.

**Suggestion:**
Create feature tests for critical flows.
```php
// tests/Feature/OrderTest.php
public function test_user_can_checkout() {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    
    Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 100,
        'amount' => 100
    ]);
    
    $this->actingAs($user)
        ->post('/order', [
            'first_name' => 'John',
            'payment_method' => 'cod',
            // ... other data
        ])
        ->assertRedirect('/');
    
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'first_name' => 'John'
    ]);
}

public function test_order_unchanged_after_product_deleted() {
    // Create order with product
    // Delete product
    // Assert order detail still shows product
}
```


---

## Screenshots Evidence

All screenshots are located in the `screenshots/` folder:

1. **1-order-detail-with-products.png** - Order detail page displays the Order Products section with complete product information
2. **2-admin-edit-product.png** - Admin edits products (changes name/price)
3. **3-admin-delete-product.png** - Admin deletes products from the system
4. **4-order-after-edit-and-delete.png** - Order details after products are edited and deleted, still displaying the original data


---

## Conclusion

All tasks have been completed successfully:
- Task 1: Order product list added to user order detail page
- Task 2: Order details preserved when products are modified/deleted
- Task 3: Project improvement suggestions provided

The implemented snapshot pattern successfully maintains order data integrity. Even when admin modifies or deletes products, order history remains accurate and accessible.

The suggestions focus on realistic improvements in security, performance, and user experience that can be implemented incrementally.

---
