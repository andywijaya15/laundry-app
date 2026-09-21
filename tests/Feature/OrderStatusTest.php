<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => 'staff',
        'is_active' => true,
    ]);

    $this->customer = Customer::create([
        'name' => 'Budi Santoso',
        'phone' => '081234567890',
        'address' => 'Jl. Merdeka No. 1',
    ]);
});

test('user can freely change order status to any workflow step', function (string $targetStatus) {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'diterima',
        'payment_status' => 'belum_bayar',
        'total_price' => 25000,
    ]);

    $response = $this->actingAs($this->user)
        ->from(route('orders.show', $order))
        ->patch(route('orders.updateStatus', $order), [
            'status' => $targetStatus,
        ]);

    $response->assertRedirect(route('orders.show', $order));
    $response->assertSessionHas('success');

    $order->refresh();
    expect($order->status)->toBe($targetStatus);
})->with([
    'cuci',
    'setrika',
    'siap_diambil',
    'selesai',
]);

test('changing status to selesai sets finished_at when order is paid', function () {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'setrika',
        'payment_status' => 'lunas',
        'total_price' => 25000,
    ]);

    $this->actingAs($this->user)
        ->patch(route('orders.updateStatus', $order), [
            'status' => 'selesai',
        ]);

    $order->refresh();
    expect($order->status)->toBe('selesai')
        ->and($order->finished_at)->not->toBeNull();
});

test('order with status selesai cannot be changed back', function (string $targetStatus) {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'selesai',
        'payment_status' => 'lunas',
        'total_price' => 25000,
        'finished_at' => now(),
    ]);

    expect($order->canTransitionTo($targetStatus))->toBeFalse();

    $response = $this->actingAs($this->user)
        ->from(route('orders.show', $order))
        ->patch(route('orders.updateStatus', $order), [
            'status' => $targetStatus,
        ]);

    $response->assertRedirect(route('orders.show', $order));
    $response->assertSessionHasErrors('status');

    $order->refresh();
    expect($order->status)->toBe('selesai');
})->with([
    'diterima',
    'cuci',
    'setrika',
    'siap_diambil',
]);

test('submitting unchanged status returns info notification', function () {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'cuci',
        'payment_status' => 'belum_bayar',
        'total_price' => 25000,
    ]);

    $response = $this->actingAs($this->user)
        ->from(route('orders.show', $order))
        ->patch(route('orders.updateStatus', $order), [
            'status' => 'cuci',
        ]);

    $response->assertRedirect(route('orders.show', $order));
    $response->assertSessionHas('info');

    $order->refresh();
    expect($order->status)->toBe('cuci');
});

test('updating status with invalid value is rejected', function () {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'diterima',
        'payment_status' => 'belum_bayar',
        'total_price' => 25000,
    ]);

    $response = $this->actingAs($this->user)
        ->patch(route('orders.updateStatus', $order), [
            'status' => 'proses_aneh',
        ]);

    $response->assertSessionHasErrors('status');

    $order->refresh();
    expect($order->status)->toBe('diterima');
});

test('order detail page renders all available status options', function () {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'diterima',
        'payment_status' => 'belum_bayar',
        'total_price' => 25000,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('orders.show', $order));

    $response->assertOk();
    $response->assertSee('Status Pesanan');
    $response->assertSee('Proses Cuci');
    $response->assertSee('Proses Setrika');
    $response->assertSee('Siap Diambil');
    $response->assertSee('Selesai');
});

test('order detail page renders locked alert when order is finished', function () {
    $order = Order::create([
        'order_code' => Order::generateOrderCode(),
        'outlet_id' => 1,
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'status' => 'selesai',
        'payment_status' => 'lunas',
        'total_price' => 25000,
        'finished_at' => now(),
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('orders.show', $order));

    $response->assertOk();
    $response->assertSee('Order Telah Selesai');
    $response->assertSee('Status order sudah final dan terkunci.');
    $response->assertDontSee('Perbarui Status');
});
