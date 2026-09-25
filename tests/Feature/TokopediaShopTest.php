<?php

use App\Models\CartItem;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(CategorySeeder::class);
    $this->seed(ProductSeeder::class);
});

it('loads Tokopedia homepage successfully with products', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('tokopedia');
    $response->assertSee('Rekomendasi Untukmu');
});

it('can search for products by keyword', function () {
    $response = $this->get('/?q=iPhone');

    $response->assertStatus(200);
    $response->assertSee('iPhone');
});

it('can view product details via JSON', function () {
    $product = Product::first();

    $response = $this->getJson('/product/'.$product->slug);

    $response->assertStatus(200);
    $response->assertJsonPath('product.slug', $product->slug);
});

it('can add a product to cart and retrieve cart count', function () {
    $product = Product::first();

    $response = $this->postJson('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('success', true);
    $response->assertJsonPath('cart_count', 2);
});

it('can complete checkout and generate invoice', function () {
    $product = Product::first();

    $this->startSession();
    $sessionId = session()->getId();

    CartItem::create([
        'session_id' => $sessionId,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $checkoutData = [
        'customer_name' => 'Budi Santoso',
        'customer_email' => 'budi@example.com',
        'customer_phone' => '081299998888',
        'shipping_address' => 'Jl. Kebon Jeruk No 10, Jakarta Barat',
        'courier' => 'Bebas Ongkir (J&T Express)',
        'payment_method' => 'GoPay',
    ];

    $response = $this->postJson('/checkout/process', $checkoutData);

    $response->assertStatus(200);
    $response->assertJsonPath('success', true);

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Budi Santoso',
        'payment_method' => 'GoPay',
    ]);
});
