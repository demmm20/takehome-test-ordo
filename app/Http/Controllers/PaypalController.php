<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaypalController extends Controller
{
    public function payment(Request $request)
    {
        try {
            // Get order ID from session (set in OrderController)
            $orderId = $request->session()->get('id');
            
            if (!$orderId) {
                return redirect()->route('checkout')
                    ->with('error', 'Order not found. Please try again.');
            }

            $order = Order::find($orderId);
            if (!$order) {
                return redirect()->route('checkout')
                    ->with('error', 'Order not found. Please try again.');
            }

            // Get cart items (not yet linked to order for PayPal)
            $cart = Cart::where('user_id', auth()->user()->id)
                ->where('order_id', null)
                ->get();
            
            if ($cart->isEmpty()) {
                return redirect()->route('checkout')
                    ->with('error', 'Your cart is empty.');
            }

            $data = [];
            
            // Prepare items for PayPal
            $data['items'] = $cart->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'name' => $product ? $product->title : 'Product',
                    'price' => (float)$item->price,
                    'desc' => 'Thank you for using PayPal',
                    'qty' => (int)$item->quantity
                ];
            })->toArray();

            $data['invoice_id'] = $order->order_number;
            $data['invoice_description'] = "Order #{$order->order_number} Invoice";
            $data['return_url'] = route('payment.success');
            $data['cancel_url'] = route('payment.cancel');

            // Calculate total
            $total = 0;
            foreach ($data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $data['total'] = $total;
            
            // Apply coupon discount if exists
            if (session('coupon')) {
                $couponDiscount = (float)session('coupon')['value'];
                $data['total'] = max(0, $data['total'] - $couponDiscount);
            }

            // Add shipping cost if exists
            if ($order->shipping_id && $order->shipping) {
                $data['total'] += (float)$order->shipping->price;
            }

            // Store order ID in session for success callback
            session()->put('paypal_order_id', $orderId);

            // Initialize PayPal with config
            $provider = new PayPal();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            // Prepare order data for PayPal Orders API v2
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $order->order_number,
                        'description' => "Order #{$order->order_number}",
                        'amount' => [
                            'currency_code' => config('paypal.currency', 'USD'),
                            'value' => number_format($data['total'], 2, '.', '')
                        ],
                        'items' => array_map(function ($item) {
                            return [
                                'name' => $item['name'],
                                'description' => $item['desc'],
                                'quantity' => (string)$item['qty'],
                                'unit_amount' => [
                                    'currency_code' => config('paypal.currency', 'USD'),
                                    'value' => number_format($item['price'], 2, '.', '')
                                ]
                            ];
                        }, $data['items'])
                    ]
                ],
                'application_context' => [
                    'return_url' => route('payment.success'),
                    'cancel_url' => route('payment.cancel'),
                    'brand_name' => config('app.name'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ];

            $response = $provider->createOrder($orderData);

            if (isset($response['id']) && isset($response['links'])) {
                $approveUrl = collect($response['links'])->where('rel', 'approve')->first();
                if ($approveUrl) {
                    // Store PayPal order ID
                    session()->put('paypal_order_token', $response['id']);
                    return redirect($approveUrl['href']);
                }
            }
            
            Log::error('PayPal Error: ' . json_encode($response));
            
            // Payment initialization failed - mark order as canceled
            if ($order && $order->payment_status == 'unpaid') {
                $order->status = 'cancel';
                $order->save();
            }
            
            session()->forget('paypal_order_id');
            session()->forget('paypal_order_token');
            
            // Cart items remain in cart (not linked to order)
            return redirect()->route('checkout')
                ->with('error', 'PayPal payment initialization failed. Your cart items are still available. Please try again.');
        } catch (\Exception $e) {
            Log::error('PayPal Payment Error: ' . $e->getMessage());
            
            // On error, mark order as canceled if exists
            $orderId = session()->get('paypal_order_id');
            if ($orderId) {
                try {
                    $order = Order::find($orderId);
                    if ($order && $order->payment_status == 'unpaid') {
                        $order->status = 'cancel';
                        $order->save();
                    }
                } catch (\Exception $ex) {
                    // Ignore
                }
            }
            
            session()->forget('paypal_order_id');
            session()->forget('paypal_order_token');
            
            // Cart items remain in cart
            return redirect()->route('checkout')
                ->with('error', 'Something went wrong. Your cart items are still available. Please try again.');
        }
    }
   
    /**
     * Handle PayPal payment cancellation
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        // Get order ID from session
        $orderId = session()->get('paypal_order_id');
        
        if ($orderId) {
            try {
                // Optionally: Delete the unpaid order or mark it as canceled
                $order = Order::find($orderId);
                if ($order && $order->payment_status == 'unpaid') {
                    // Mark order as canceled
                    $order->status = 'cancel';
                    $order->save();
                    // Note: Cart items are not linked yet, so they remain in cart
                }
            } catch (\Exception $e) {
                Log::error('PayPal Cancel Error: ' . $e->getMessage());
            }
            
            // Clear PayPal session data
            session()->forget('paypal_order_id');
            session()->forget('paypal_order_token');
        }
        
        // Cart items remain available since they weren't linked to order
        return redirect()->route('checkout')
            ->with('error', 'Payment was canceled. Your cart items are still available.');
    }
  
    /**
     * Handle successful PayPal payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
        try {
            if (!$request->has('token')) {
                return redirect()->route('checkout')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

            $paypalOrderId = $request->input('token');
            
            // Get order ID from session
            $orderId = session()->get('paypal_order_id');
            
            if (!$orderId) {
                return redirect()->route('checkout')
                    ->with('error', 'Order not found. Please contact support.');
            }

            $order = Order::find($orderId);
            if (!$order) {
                return redirect()->route('checkout')
                    ->with('error', 'Order not found. Please contact support.');
            }

            // Initialize PayPal
            $provider = new PayPal();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            // Capture the payment
            $paymentResponse = $provider->capturePaymentOrder($paypalOrderId);

            if (isset($paymentResponse['status']) && 
                strtoupper($paymentResponse['status']) === 'COMPLETED') {
                
                // Payment successful - NOW link cart items to order
                Cart::where('user_id', auth()->user()->id)
                    ->where('order_id', null)
                    ->update(['order_id' => $order->id]);
                
                // Update order payment status
                $order->payment_status = 'paid';
                $order->save();

                // Send notification to admin
                $admin = \App\Models\User::where('role', 'admin')->first();
                if ($admin) {
                    $details = [
                        'title' => 'New order created',
                        'actionURL' => route('order.show', $order->id),
                        'fas' => 'fa-file-alt'
                    ];
                    \Notification::send($admin, new \App\Notifications\StatusNotification($details));
                }

                // Clear session data only after successful payment
                session()->forget('cart');
                session()->forget('coupon');
                session()->forget('paypal_order_id');
                session()->forget('paypal_order_token');

                return redirect()->route('home')
                    ->with('success', 'Payment successful! Thank you for your order.');
            } else {
                Log::error('PayPal Payment Processing Error: ' . json_encode($paymentResponse));
                
                // Payment failed - cart items remain in cart (not linked to order)
                // Optionally mark order as canceled
                if ($order) {
                    $order->status = 'cancel';
                    $order->save();
                }
                
                session()->forget('paypal_order_id');
                session()->forget('paypal_order_token');
                
                return redirect()->route('checkout')
                    ->with('error', 'Payment processing failed. Your cart items are still available. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('PayPal Success Error: ' . $e->getMessage());
            
            // On error, ensure cart items remain available
            $orderId = session()->get('paypal_order_id');
            if ($orderId) {
                try {
                    $order = Order::find($orderId);
                    if ($order && $order->payment_status == 'unpaid') {
                        $order->status = 'cancel';
                        $order->save();
                    }
                } catch (\Exception $ex) {
                    // Ignore
                }
            }
            
            session()->forget('paypal_order_id');
            session()->forget('paypal_order_token');
            
            return redirect()->route('checkout')
                ->with('error', 'Something went wrong. Your cart items are still available. Please contact support if payment was deducted.');
        }
    }
}
