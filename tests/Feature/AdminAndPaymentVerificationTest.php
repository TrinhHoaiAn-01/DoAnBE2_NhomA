<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminAndPaymentVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
            ]),
        ]);
    }

    public function test_admin_dashboard_accessibility(): void
    {
        // 1. Create admin user
        $admin = User::create([
            'name' => 'Admin Test User',
            'username' => 'admin_test_user',
            'email' => 'admin_test_user@example.com',
            'password' => bcrypt('password'),
            'role_id' => 5,
            'status' => true,
        ]);

        // 2. Access admin dashboard as admin
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // 3. Access admin users list
        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);

        // 4. Access admin products list
        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
    }

    public function test_customer_checkout_and_payment_flow(): void
    {
        // 1. Create a customer user
        $customer = User::create([
            'name' => 'Customer User',
            'username' => 'customer_user',
            'email' => 'customer_user@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'status' => true,
        ]);

        // 2. Create a category and product
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category-slug',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product-slug',
            'sku' => 'TESTSKU123',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
            'image_url' => 'https://via.placeholder.com/150',
            'description' => 'Test description',
        ]);

        // 3. Add product to cart
        $response = $this->actingAs($customer)->post(route('cart.add', $product), [
            'quantity' => 2,
        ]);
        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(2, session('cart')[$product->id]);

        // 4. Access checkout page
        $response = $this->actingAs($customer)->get(route('checkout.index'));
        $response->assertStatus(200);

        // 5. Submit checkout order
        $orderData = [
            'customer_name' => 'John Doe',
            'customer_phone' => '0901234567',
            'shipping_address' => '123 Test Street',
            'shipping_district' => 'noi_thanh',
            'shipping_service' => 'standard',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_time_slot' => 'afternoon',
            'payment_method' => 'bank_transfer',
            'note' => 'Deliver in afternoon',
        ];

        $response = $this->actingAs($customer)->post(route('checkout.store'), $orderData);
        
        // Debugging
        if (session('errors')) {
            dump(session('errors')->getMessages());
        }
        if (session('error')) {
            dump(session('error'));
        }
        
        // Find created order
        $order = Order::where('user_id', $customer->id)->first();
        $this->assertNotNull($order);
        
        // Check order status is pending
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(200000, $order->subtotal);

        // Should redirect to demo payment gateway page
        $response->assertRedirect(route('payment.demo', $order));

        // 6. Access payment demo page
        $response = $this->actingAs($customer)->get(route('payment.demo', $order));
        $response->assertStatus(200);

        // 7. Confirm payment via banking demo POST
        $response = $this->actingAs($customer)->post(route('payment.confirm', $order));
        $response->assertRedirect(route('checkout.success', $order));

        // 8. Refresh order status and verify it is updated (or marked as paid/processing depending on logic)
        $order->refresh();
        $this->assertEquals('processing', $order->status); // standard logic shifts to processing on payment
    }
}
