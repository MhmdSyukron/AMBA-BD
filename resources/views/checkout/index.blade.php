@extends('layouts.app')

@section('title', 'Checkout Belanja - Tokopedia')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
            <i class="fa-solid fa-cart-flatbed-suitcases text-tokopedia"></i> Checkout Pengiriman
        </h1>
        <a href="{{ route('home') }}" class="text-xs font-semibold text-tokopedia hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali Belanja
        </a>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- Left Column: Customer Details & Order Items -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Shipping Address Box -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-location-dot text-tokopedia"></i> Alamat Pengiriman
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap Penerima</label>
                        <input type="text" name="customer_name" value="Naufal Dev" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 focus:border-tokopedia focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="customer_phone" value="081234567890" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 focus:border-tokopedia focus:outline-none">
                    </div>
                </div>

                <div class="text-xs">
                    <label class="block font-semibold text-gray-700 mb-1">Email Konfirmasi</label>
                    <input type="email" name="customer_email" value="naufal@example.com" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 focus:border-tokopedia focus:outline-none">
                </div>

                <div class="text-xs">
                    <label class="block font-semibold text-gray-700 mb-1">Alamat Lengkap (Jalan, RT/RW, Kecamatan, Kota)</label>
                    <textarea name="shipping_address" rows="3" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 focus:border-tokopedia focus:outline-none">Jl. Jendral Sudirman No. 128, RT 05 / RW 02, Karet Semanggi, Setiabudi, Jakarta Selatan 12930</textarea>
                </div>
            </div>

            <!-- 2. Ordered Items -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-bag-shopping text-tokopedia"></i> Barang yang Dibeli
                </h3>

                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4 py-2 border-b border-gray-100 last:border-0">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                        <div class="flex-1 text-xs">
                            <span class="text-[10px] font-bold text-tokopedia bg-tokopedia-light px-2 py-0.5 rounded">{{ $item->product->store_name }}</span>
                            <h4 class="font-bold text-gray-800 leading-tight mt-1">{{ $item->product->name }}</h4>
                            <div class="text-gray-500 mt-1">
                                {{ $item->quantity }} x <span class="font-bold text-gray-900">{{ $item->product->formatted_price }}</span>
                            </div>
                        </div>
                        <div class="text-sm font-extrabold text-gray-900">
                            {{ $item->formatted_subtotal }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Shipping Courier & Payment Method -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-truck-fast text-tokopedia"></i> Durasi & Kurir Pengiriman
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <label class="p-3 border border-tokopedia bg-tokopedia-light/30 rounded-xl cursor-pointer flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="courier" value="Bebas Ongkir (J&T Express)" checked class="text-tokopedia focus:ring-tokopedia">
                            <div>
                                <span class="font-bold text-gray-900 block">Bebas Ongkir (J&T)</span>
                                <span class="text-[10px] text-gray-500">Estimasi 1-2 Hari Kerja</span>
                            </div>
                        </div>
                        <span class="font-bold text-tokopedia">Rp 0 (Hemat)</span>
                    </label>

                    <label class="p-3 border border-gray-200 rounded-xl cursor-pointer flex items-center justify-between hover:border-tokopedia">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="courier" value="GoSend Instant" class="text-tokopedia focus:ring-tokopedia">
                            <div>
                                <span class="font-bold text-gray-900 block">GoSend Instant</span>
                                <span class="text-[10px] text-gray-500">Tiba dalam 3 Jam</span>
                            </div>
                        </div>
                        <span class="font-bold text-gray-800">Rp 25.000</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- Right Column: Summary & Payment -->
        <div class="space-y-6">

            <!-- Payment Method Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-wallet text-tokopedia"></i> Metode Pembayaran
                </h3>

                <div class="space-y-2 text-xs">
                    <label class="p-3 border border-tokopedia bg-tokopedia-light/30 rounded-xl cursor-pointer flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="GoPay" checked class="text-tokopedia focus:ring-tokopedia">
                            <span class="font-bold text-gray-900">GoPay / GoPay Later</span>
                        </div>
                        <span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded">Cashback 10%</span>
                    </label>

                    <label class="p-3 border border-gray-200 rounded-xl cursor-pointer flex items-center justify-between hover:border-tokopedia">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="QRIS Instant" class="text-tokopedia focus:ring-tokopedia">
                            <span class="font-bold text-gray-900">QRIS (BCA, Mandiri, ShopeePay)</span>
                        </div>
                    </label>

                    <label class="p-3 border border-gray-200 rounded-xl cursor-pointer flex items-center justify-between hover:border-tokopedia">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="BCA Virtual Account" class="text-tokopedia focus:ring-tokopedia">
                            <span class="font-bold text-gray-900">Virtual Account BCA</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 space-y-4 sticky top-24">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100">Ringkasan Belanja</h3>

                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <span>Total Harga ({{ $cartItems->sum('quantity') }} barang)</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Ongkos Kirim</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-tokopedia font-semibold">
                        <span>Promo Bebas Ongkir</span>
                        <span>-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 flex justify-between items-center text-sm font-extrabold text-gray-900">
                    <span>Total Tagihan</span>
                    <span class="text-tokopedia text-lg">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="w-full bg-tokopedia hover:bg-tokopedia-dark text-white font-extrabold py-3.5 rounded-xl shadow-lg shadow-tokopedia/30 text-sm transition">
                    Bayar Sekarang
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
