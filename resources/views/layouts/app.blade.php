<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tokopedia - Jual Beli Online Aman & Nyaman')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tokopedia: {
                            DEFAULT: '#03AC0E',
                            dark: '#028A0B',
                            light: '#E8F8EA',
                            accent: '#FF5722',
                            yellow: '#FFC107',
                            bg: '#F3F4F6'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .tokopedia-shadow {
            box-shadow: 0 1px 6px 0 rgba(49, 53, 59, 0.12);
        }
        .tokopedia-card-hover {
            transition: all 0.2s ease-in-out;
        }
        .tokopedia-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px 0 rgba(49, 53, 59, 0.16);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #03AC0E;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans text-gray-800 antialiased selection:bg-tokopedia selection:text-white"
      x-data="tokopediaApp()"
      x-init="initCart()">

    <!-- Toast Notification -->
    <div x-cloak
         x-show="toast.show"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3.5 rounded-xl shadow-2xl border border-gray-700">
        <i class="fa-solid fa-circle-check text-tokopedia text-xl"></i>
        <span x-text="toast.message" class="text-sm font-semibold"></span>
    </div>

    <!-- Top Mini Header -->
    <div class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 py-1.5 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-tokopedia transition flex items-center gap-1.5"><i class="fa-solid fa-mobile-screen"></i> Download Tokopedia App</a>
                <a href="#" class="hover:text-tokopedia transition">Mitra Tokopedia</a>
                <a href="#" class="hover:text-tokopedia transition">Mulai Berjualan</a>
                <a href="#" class="hover:text-tokopedia transition">Promo & Voucher</a>
                <a href="#" class="hover:text-tokopedia transition">Tokopedia Care</a>
            </div>
            <div class="flex items-center space-x-6">
                <button @click="openAdminModal = true" class="hover:text-tokopedia transition flex items-center gap-1 text-tokopedia font-semibold">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Produk Baru
                </button>
                <a href="#" class="hover:text-tokopedia transition">Bantuan</a>
            </div>
        </div>
    </div>

    <!-- Main Navbar Header -->
    <header class="bg-white sticky top-0 z-40 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center gap-4 md:gap-6">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
                    <div class="w-10 h-10 bg-tokopedia rounded-xl flex items-center justify-center text-white shadow-md shadow-tokopedia/30 group-hover:scale-105 transition">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-2xl font-extrabold text-tokopedia tracking-tight">tokopedia</span>
                        <span class="block -mt-1.5 text-[10px] text-gray-400 font-semibold tracking-wider">OFFICIAL STORE</span>
                    </div>
                </a>

                <!-- Kategori Dropdown -->
                <div class="relative hidden lg:block" x-data="{ openCat: false }">
                    <button @click="openCat = !openCat" @click.outside="openCat = false" class="text-xs font-semibold text-gray-700 hover:text-tokopedia px-2 py-1.5 rounded-lg hover:bg-gray-100 transition flex items-center gap-1.5">
                        <span>Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{'rotate-180': openCat}"></i>
                    </button>
                    <!-- Category Dropdown menu -->
                    <div x-cloak x-show="openCat" x-transition class="absolute top-full left-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-50">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-xs hover:bg-tokopedia-light hover:text-tokopedia font-medium transition">
                            <i class="fa-solid fa-border-all text-tokopedia w-4"></i> Semua Kategori
                        </a>
                        @foreach($categories ?? [] as $cat)
                        <a href="{{ route('home', ['category' => $cat->slug]) }}" class="flex items-center justify-between px-4 py-2 text-xs hover:bg-tokopedia-light hover:text-tokopedia font-medium transition">
                            <span class="flex items-center gap-3">
                                <i class="{{ $cat->icon ?? 'fa-solid fa-tag' }} text-gray-400 w-4"></i>
                                {{ $cat->name }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-semibold bg-gray-100 px-1.5 py-0.5 rounded">{{ $cat->products_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Search Bar Form -->
                <form action="{{ route('home') }}" method="GET" class="flex-1 max-w-3xl">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari di Tokopedia (misal: iPhone 15, Laptop, Sepatu)..."
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-12 py-2 text-xs md:text-sm focus:outline-none focus:border-tokopedia focus:bg-white focus:ring-1 focus:ring-tokopedia transition shadow-inner">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        @if(request('q'))
                        <a href="{{ route('home') }}" class="absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                        @endif
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 bg-tokopedia hover:bg-tokopedia-dark text-white px-3 py-1.5 rounded-md text-xs font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Actions: Cart & Messages -->
                <div class="flex items-center gap-3 md:gap-5">
                    <!-- Cart Button -->
                    <button @click="toggleCartDrawer()" class="relative p-2 text-gray-700 hover:text-tokopedia hover:bg-gray-100 rounded-xl transition">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span x-cloak
                              x-show="cartCount > 0"
                              x-text="cartCount"
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white animate-bounce">
                        </span>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-700 hover:text-tokopedia hover:bg-gray-100 rounded-xl transition hidden sm:block">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-tokopedia rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- User Profile / Login -->
                    <div class="h-6 w-px bg-gray-300 hidden md:block"></div>
                    <div class="flex items-center gap-2">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&q=80" alt="Avatar" class="w-8 h-8 rounded-full border border-tokopedia object-cover">
                        <span class="text-xs font-bold text-gray-800 hidden lg:block">Naufal Dev</span>
                    </div>
                </div>

            </div>

            <!-- Sub Header: Search Trending Keywords -->
            <div class="flex items-center space-x-4 mt-2 overflow-x-auto text-[11px] text-gray-500 whitespace-nowrap scrollbar-none hidden md:flex pl-36">
                <span class="font-semibold text-gray-400">Pencarian Populer:</span>
                <a href="{{ route('home', ['q' => 'iPhone 15']) }}" class="hover:text-tokopedia transition">iPhone 15 Pro</a>
                <a href="{{ route('home', ['q' => 'Samsung']) }}" class="hover:text-tokopedia transition">Samsung S24</a>
                <a href="{{ route('home', ['q' => 'MacBook']) }}" class="hover:text-tokopedia transition">MacBook M3</a>
                <a href="{{ route('home', ['q' => 'Sepatu']) }}" class="hover:text-tokopedia transition">Sepatu Jordan</a>
                <a href="{{ route('home', ['q' => 'Jaket']) }}" class="hover:text-tokopedia transition">Jaket Eiger</a>
                <a href="{{ route('home', ['q' => 'Air Fryer']) }}" class="hover:text-tokopedia transition">Air Fryer Digital</a>
            </div>

        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Cart Side Drawer -->
    <div x-cloak x-show="cartDrawerOpen" class="relative z-50">
        <div x-show="cartDrawerOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="cartDrawerOpen = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="cartDrawerOpen"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col">

                <!-- Drawer Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-tokopedia-light">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-tokopedia text-lg"></i>
                        <h2 class="text-base font-bold text-gray-800">Keranjang Belanja (<span x-text="cartCount"></span>)</h2>
                    </div>
                    <button @click="cartDrawerOpen = false" class="text-gray-400 hover:text-gray-600 text-lg p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-if="cartItems.length === 0">
                        <div class="text-center py-16">
                            <i class="fa-solid fa-cart-flatbed text-6xl text-gray-300 mb-4 block"></i>
                            <p class="text-sm font-semibold text-gray-600">Wah, keranjang belanjaanmu kosong!</p>
                            <p class="text-xs text-gray-400 mt-1">Yuk, isi dengan barang-barang impianmu sekarang.</p>
                            <button @click="cartDrawerOpen = false" class="mt-5 bg-tokopedia text-white text-xs font-bold px-6 py-2.5 rounded-lg hover:bg-tokopedia-dark transition">
                                Mulai Belanja
                            </button>
                        </div>
                    </template>

                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex gap-3 bg-gray-50 p-3 rounded-xl border border-gray-200 relative group">
                            <img :src="item.image_url" :alt="item.name" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <h4 x-text="item.name" class="text-xs font-bold text-gray-800 line-clamp-2 leading-tight"></h4>
                                <div class="text-[10px] text-tokopedia font-semibold mt-1" x-text="item.store_name"></div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs font-extrabold text-gray-900" x-text="item.formatted_subtotal"></span>
                                    
                                    <!-- Qty buttons -->
                                    <div class="flex items-center border border-gray-300 rounded-md bg-white text-xs">
                                        <button @click="updateQty(item.id, item.quantity - 1)" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100 font-bold">-</button>
                                        <span x-text="item.quantity" class="px-2 font-semibold"></span>
                                        <button @click="updateQty(item.id, item.quantity + 1)" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100 font-bold">+</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Remove Item -->
                            <button @click="removeItem(item.id)" class="text-gray-400 hover:text-red-500 text-xs self-start">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Drawer Footer -->
                <div x-show="cartItems.length > 0" class="p-6 border-t border-gray-200 bg-gray-50 space-y-3">
                    <div class="flex justify-between items-center text-sm font-bold text-gray-800">
                        <span>Total Harga</span>
                        <span class="text-tokopedia text-base" x-text="formattedSubtotal"></span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="w-full bg-tokopedia hover:bg-tokopedia-dark text-white font-bold py-3 rounded-xl text-center block text-sm shadow-lg shadow-tokopedia/30 transition">
                        Lanjut Beli (<span x-text="cartCount"></span> Barang)
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Admin Add Product Modal -->
    <div x-cloak x-show="openAdminModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.outside="openAdminModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-box-open text-tokopedia"></i> Tambah Produk Baru
                </h3>
                <button @click="openAdminModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('products.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Kategori Produk</label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                        @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" required placeholder="Contoh: Wireless Earbuds Bluetooth 5.3" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="price" required placeholder="499000" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Harga Coret (Coret)</label>
                        <input type="number" name="original_price" placeholder="799000" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Stok Barang</label>
                        <input type="number" name="stock" value="50" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kota Asal Toko</label>
                        <input type="text" name="store_location" value="Jakarta Selatan" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Toko / Official Store</label>
                    <input type="text" name="store_name" value="Tokopedia Official Store" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">URL Gambar Produk (Unsplash / Direct Link)</label>
                    <input type="url" name="image_url" required placeholder="https://images.unsplash.com/..." class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Deskripsi Produk</label>
                    <textarea name="description" rows="2" required placeholder="Jelaskan spesifikasi dan keunggulan barang..." class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:border-tokopedia focus:outline-none"></textarea>
                </div>
                <div class="flex items-center gap-4 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700">
                        <input type="checkbox" name="is_official_store" checked class="rounded text-tokopedia focus:ring-tokopedia"> Official Store
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700">
                        <input type="checkbox" name="is_flash_sale" class="rounded text-tokopedia focus:ring-tokopedia"> Masuk Flash Sale
                    </label>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" @click="openAdminModal = false" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-tokopedia text-white rounded-lg font-bold hover:bg-tokopedia-dark">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tokopedia Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20 pt-12 pb-8 text-xs text-gray-600">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-5 gap-8">
            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-3">Tokopedia</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-tokopedia">Tentang Tokopedia</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Hak Kekayaan Intelektual</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Karir</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Blog Tokopedia</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Tokopedia Parents</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-3">Beli</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-tokopedia">Tagihan & Top Up</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Tokopedia COD</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Bebas Ongkir</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Keuangan & Asuransi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-3">Jual</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-tokopedia">Pusat Edukasi Seller</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Mitra Toppers</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Daftar Official Store</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-3">Bantuan & Panduan</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-tokopedia">Tokopedia Care</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Syarat dan Ketentuan</a></li>
                    <li><a href="#" class="hover:text-tokopedia">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-3">Ikuti Kami</h4>
                <div class="flex gap-3 text-lg text-gray-500 mb-4">
                    <a href="#" class="hover:text-tokopedia"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-tokopedia"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-tokopedia"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="hover:text-tokopedia"><i class="fa-brands fa-youtube"></i></a>
                </div>
                <h4 class="font-bold text-sm text-gray-900 mb-2">Metode Pembayaran</h4>
                <div class="flex flex-wrap gap-2 text-xs font-bold text-gray-400">
                    <span class="bg-gray-100 px-2 py-1 rounded">GoPay</span>
                    <span class="bg-gray-100 px-2 py-1 rounded">QRIS</span>
                    <span class="bg-gray-100 px-2 py-1 rounded">BCA</span>
                    <span class="bg-gray-100 px-2 py-1 rounded">Mandiri</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-gray-200 text-center text-gray-400">
            &copy; 2009 - {{ date('Y') }}, PT Tokopedia. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>

    <!-- Alpine App State Logic -->
    <script>
        function tokopediaApp() {
            return {
                cartDrawerOpen: false,
                openAdminModal: false,
                cartCount: {{ $cartCount ?? 0 }},
                cartItems: [],
                subtotal: 0,
                formattedSubtotal: 'Rp 0',
                toast: {
                    show: false,
                    message: ''
                },
                initCart() {
                    this.fetchCart();
                },
                fetchCart() {
                    fetch('{{ route("cart.index") }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.items || [];
                        this.cartCount = data.cart_count || 0;
                        this.subtotal = data.subtotal || 0;
                        this.formattedSubtotal = data.formatted_subtotal || 'Rp 0';
                    });
                },
                addToCart(productId, qty = 1) {
                    fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId, quantity: qty })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.fetchCart();
                            this.showToast(data.message);
                        }
                    });
                },
                updateQty(cartItemId, newQty) {
                    if (newQty < 1) return this.removeItem(cartItemId);
                    fetch(`/cart/update/${cartItemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ quantity: newQty })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.items || [];
                        this.cartCount = data.cart_count || 0;
                        this.formattedSubtotal = data.formatted_subtotal || 'Rp 0';
                    });
                },
                removeItem(cartItemId) {
                    fetch(`/cart/remove/${cartItemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.items || [];
                        this.cartCount = data.cart_count || 0;
                        this.formattedSubtotal = data.formatted_subtotal || 'Rp 0';
                        this.showToast('Produk dihapus dari keranjang.');
                    });
                },
                toggleCartDrawer() {
                    this.cartDrawerOpen = !this.cartDrawerOpen;
                },
                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
