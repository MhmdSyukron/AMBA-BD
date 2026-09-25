@extends('layouts.app')

@section('title', 'Nota Pembayaran - ' . $order->invoice_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <!-- Success Banner Card -->
    <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200 text-center space-y-6 relative overflow-hidden">
        <div class="w-20 h-20 bg-tokopedia-light text-tokopedia rounded-full flex items-center justify-center text-4xl mx-auto ring-8 ring-tokopedia-light/50">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div>
            <span class="bg-green-100 text-green-700 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">PEMBAYARAN BERHASIL</span>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-2">Terima Kasih Atas Pesanan Anda!</h1>
            <p class="text-xs text-gray-500 mt-1">Pesanan sedang diproses oleh penjual dan akan segera dikirim.</p>
        </div>

        <!-- Receipt Box -->
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 text-left space-y-4 text-xs">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Nomor Invoice</span>
                    <span class="font-mono font-extrabold text-gray-900 text-sm">{{ $order->invoice_number }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Waktu Transaksi</span>
                    <span class="font-bold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
            </div>

            <!-- Items -->
            <div class="space-y-3">
                <span class="font-bold text-gray-700 block">Rincian Barang:</span>
                @foreach($order->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200">
                        <div>
                            <h4 class="font-bold text-gray-900 line-clamp-1 max-w-xs">{{ $item->product->name }}</h4>
                            <span class="text-gray-400">{{ $item->quantity }}x {{ $item->formatted_price }}</span>
                        </div>
                    </div>
                    <span class="font-extrabold text-gray-900">{{ $item->formatted_subtotal }}</span>
                </div>
                @endforeach
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-200 text-[11px]">
                <div>
                    <span class="text-gray-400 block font-semibold">Penerima & Alamat:</span>
                    <span class="font-bold text-gray-900 block">{{ $order->customer_name }} ({{ $order->customer_phone }})</span>
                    <span class="text-gray-600 block mt-0.5">{{ $order->shipping_address }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold">Metode & Kurir:</span>
                    <span class="font-bold text-gray-900 block">{{ $order->payment_method }}</span>
                    <span class="text-gray-600 block mt-0.5">{{ $order->courier }}</span>
                </div>
            </div>

            <!-- Total -->
            <div class="pt-3 border-t border-gray-200 flex justify-between items-center text-sm font-extrabold">
                <span class="text-gray-900">Total Pembayaran Lunas</span>
                <span class="text-tokopedia text-lg">{{ $order->formatted_total_amount }}</span>
            </div>
        </div>

        <div class="flex justify-center gap-4 pt-2">
            <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-6 py-3 rounded-xl text-xs flex items-center gap-2 transition">
                <i class="fa-solid fa-print"></i> Cetak Invoice
            </button>
            <a href="{{ route('home') }}" class="bg-tokopedia hover:bg-tokopedia-dark text-white font-bold px-6 py-3 rounded-xl text-xs flex items-center gap-2 shadow-lg shadow-tokopedia/30 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
