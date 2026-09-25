@extends('layouts.app')

@section('title', 'Tokopedia - Situs Jual Beli Online Terlengkap & Terpercaya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 space-y-8" x-data="homeFeed()">

    <!-- Session Flash Alerts -->
    @if(session('success'))
    <div class="bg-tokopedia-light border border-tokopedia text-tokopedia-dark px-4 py-3 rounded-xl text-xs font-semibold flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-tokopedia-dark"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Hero Banner Carousel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Carousel -->
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden shadow-lg group bg-slate-900 h-64 md:h-72">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0 bg-cover bg-center flex flex-col justify-end p-6 md:p-8"
                     :style="`background-image: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.1)), url('${slide.image}')`">
                    <span class="bg-tokopedia text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider w-max mb-2" x-text="slide.tag"></span>
                    <h2 class="text-white text-xl md:text-3xl font-extrabold leading-tight max-w-xl" x-text="slide.title"></h2>
                    <p class="text-gray-200 text-xs md:text-sm mt-1 max-w-lg" x-text="slide.subtitle"></p>
                </div>
            </template>

            <!-- Carousel Controls -->
            <button @click="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-gray-800 flex items-center justify-center shadow-md transition">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
            <button @click="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-gray-800 flex items-center justify-center shadow-md transition">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>

            <!-- Slide Indicators -->
            <div class="absolute bottom-3 right-6 flex space-x-1.5 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index"
                            class="h-2 rounded-full transition-all"
                            :class="activeSlide === index ? 'w-6 bg-tokopedia' : 'w-2 bg-white/60'"></button>
                </template>
            </div>
        </div>

        <!-- Side Promo Banners -->
        <div class="flex flex-col gap-4">
            <div class="flex-1 bg-gradient-to-r from-emerald-600 to-tokopedia rounded-2xl p-5 text-white flex flex-col justify-between shadow-md relative overflow-hidden">
                <div class="z-10">
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded">Tokopedia NOW!</span>
                    <h3 class="text-base font-extrabold mt-1">Belanja Bahan Makanan 15 Menit Sampai!</h3>
                </div>
                <div class="z-10 flex items-center justify-between mt-3">
                    <span class="text-xs font-bold bg-white text-tokopedia px-3 py-1.5 rounded-lg shadow">Pesan Sekarang</span>
                    <i class="fa-solid fa-bolt text-yellow-300 text-2xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
            </div>

            <div class="flex-1 bg-gradient-to-r from-purple-700 to-indigo-600 rounded-2xl p-5 text-white flex flex-col justify-between shadow-md relative overflow-hidden">
                <div class="z-10">
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded">Official Store</span>
                    <h3 class="text-base font-extrabold mt-1">Garansi 100% Ori atau Uang Kembali</h3>
                </div>
                <div class="z-10 flex items-center justify-between mt-3">
                    <span class="text-xs font-bold bg-white text-purple-700 px-3 py-1.5 rounded-lg shadow">Cek Brand</span>
                    <i class="fa-solid fa-shield-halved text-purple-200 text-2xl"></i>
                </div>
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>

    <!-- Category Bar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200">
        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shapes text-tokopedia"></i> Kategori Pilihan
        </h3>
        <div class="grid grid-cols-4 sm:grid-cols-4 md:grid-cols-8 gap-4">
            <a href="{{ route('home') }}" class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-700 group-hover:bg-tokopedia-light group-hover:text-tokopedia transition shadow-inner">
                    <i class="fa-solid fa-border-all text-xl"></i>
                </div>
                <span class="text-[11px] font-bold mt-2 text-gray-700 group-hover:text-tokopedia">Semua</span>
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('home', ['category' => $cat->slug]) }}" class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center text-tokopedia group-hover:bg-tokopedia group-hover:text-white transition shadow-sm relative">
                    <i class="{{ $cat->icon ?? 'fa-solid fa-tag' }} text-xl"></i>
                    @if($cat->badge)
                    <span class="absolute -top-1.5 -right-1 bg-red-500 text-white text-[9px] font-extrabold px-1.5 py-0.2 rounded-full ring-2 ring-white">{{ $cat->badge }}</span>
                    @endif
                </div>
                <span class="text-[11px] font-bold mt-2 text-gray-700 group-hover:text-tokopedia line-clamp-1">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Flash Sale Section -->
    @if($flashSaleProducts->count() > 0)
    <div class="bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center text-yellow-300 text-xl font-bold">
                    <i class="fa-solid fa-bolt animate-pulse"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold uppercase tracking-wide flex items-center gap-2">
                        FLASH SALE <span class="bg-yellow-400 text-red-900 text-xs px-2 py-0.5 rounded font-black">s/d 90%</span>
                    </h3>
                    <p class="text-xs text-red-100">Diskon gede-gedean berakhir dalam:</p>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="flex items-center space-x-2 text-xs font-bold">
                <span class="bg-black/40 backdrop-blur px-3 py-1.5 rounded-lg border border-white/20 text-yellow-300" x-text="timer.hours">03</span> :
                <span class="bg-black/40 backdrop-blur px-3 py-1.5 rounded-lg border border-white/20 text-yellow-300" x-text="timer.minutes">45</span> :
                <span class="bg-black/40 backdrop-blur px-3 py-1.5 rounded-lg border border-white/20 text-yellow-300" x-text="timer.seconds">12</span>
            </div>
        </div>

        <!-- Flash Sale Horizontal Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($flashSaleProducts as $prod)
            <div class="bg-white rounded-xl overflow-hidden text-gray-800 shadow-md group hover:shadow-2xl transition flex flex-col justify-between">
                <div>
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded">
                            -{{ $prod->discount_percent }}%
                        </span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold line-clamp-1 text-gray-800 mb-1">{{ $prod->name }}</h4>
                        <div class="text-sm font-black text-red-600">{{ $prod->formatted_price }}</div>
                        @if($prod->original_price)
                        <div class="text-[10px] text-gray-400 line-through">{{ $prod->formatted_original_price }}</div>
                        @endif

                        <!-- Stock Bar -->
                        <div class="mt-2">
                            <div class="w-full bg-red-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-red-500 h-full rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-[9px] font-bold text-red-600 mt-1 block">Segera Habis!</span>
                        </div>
                    </div>
                </div>
                <div class="p-3 pt-0">
                    <button @click="addToCart({{ $prod->id }})" class="w-full bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold py-1.5 rounded-lg transition">
                        Beli Sekarang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Main Product Feed Header & Filters -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-tokopedia"></i> Rekomendasi Untukmu
                </h3>
                <p class="text-xs text-gray-500">Menampilkan {{ $products->count() }} produk pilihan</p>
            </div>

            <!-- Sorting Tabs -->
            <div class="flex items-center space-x-2 overflow-x-auto text-xs font-semibold">
                <a href="{{ route('home', array_merge(request()->query(), ['sort' => 'latest'])) }}"
                   class="px-3 py-1.5 rounded-xl border transition {{ request('sort', 'latest') === 'latest' ? 'bg-tokopedia text-white border-tokopedia' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                   Terbaru
                </a>
                <a href="{{ route('home', array_merge(request()->query(), ['sort' => 'top_seller'])) }}"
                   class="px-3 py-1.5 rounded-xl border transition {{ request('sort') === 'top_seller' ? 'bg-tokopedia text-white border-tokopedia' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                   Paling Laris
                </a>
                <a href="{{ route('home', array_merge(request()->query(), ['sort' => 'rating'])) }}"
                   class="px-3 py-1.5 rounded-xl border transition {{ request('sort') === 'rating' ? 'bg-tokopedia text-white border-tokopedia' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                   Ulasan Tertinggi
                </a>
                <a href="{{ route('home', array_merge(request()->query(), ['sort' => 'price_low'])) }}"
                   class="px-3 py-1.5 rounded-xl border transition {{ request('sort') === 'price_low' ? 'bg-tokopedia text-white border-tokopedia' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                   Harga Terendah
                </a>
                <a href="{{ route('home', array_merge(request()->query(), ['sort' => 'price_high'])) }}"
                   class="px-3 py-1.5 rounded-xl border transition {{ request('sort') === 'price_high' ? 'bg-tokopedia text-white border-tokopedia' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                   Harga Tertinggi
                </a>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($products as $product)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 tokopedia-card-hover flex flex-col justify-between group">
                <div>
                    <!-- Product Image -->
                    <div class="relative aspect-square overflow-hidden bg-gray-50 cursor-pointer" @click="openProductModal('{{ $product->slug }}')">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                        @if($product->discount_percent > 0)
                        <span class="absolute top-2.5 left-2.5 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded shadow">
                            {{ $product->discount_percent }}% OFF
                        </span>
                        @endif

                        @if($product->is_official_store)
                        <span class="absolute bottom-2.5 left-2.5 bg-purple-700 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow">
                            <i class="fa-solid fa-check-double text-[8px]"></i> Official
                        </span>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="p-3.5 space-y-1.5">
                        <!-- Name -->
                        <h4 @click="openProductModal('{{ $product->slug }}')" class="text-xs font-bold text-gray-800 line-clamp-2 hover:text-tokopedia cursor-pointer leading-tight">
                            {{ $product->name }}
                        </h4>

                        <!-- Price -->
                        <div class="pt-1">
                            <div class="text-sm font-extrabold text-gray-900">{{ $product->formatted_price }}</div>
                            @if($product->original_price)
                            <div class="text-[10px] text-gray-400 line-through">{{ $product->formatted_original_price }}</div>
                            @endif
                        </div>

                        <!-- Store Location -->
                        <div class="text-[11px] text-gray-500 flex items-center gap-1">
                            <i class="fa-solid fa-location-dot text-gray-400 text-[10px]"></i>
                            <span>{{ $product->store_location }}</span>
                        </div>

                        <!-- Rating & Sold -->
                        <div class="flex items-center text-[10px] text-gray-500 space-x-1 pt-1">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-bold text-gray-700">{{ $product->rating }}</span>
                            <span>•</span>
                            <span>{{ $product->sold_count > 1000 ? round($product->sold_count/1000, 1).'rb+' : $product->sold_count }} terjual</span>
                        </div>
                    </div>
                </div>

                <!-- Card Action Footer -->
                <div class="p-3.5 pt-0 flex gap-2">
                    <button @click="openProductModal('{{ $product->slug }}')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2 rounded-xl transition">
                        Detail
                    </button>
                    <button @click="addToCart({{ $product->id }})" class="bg-tokopedia hover:bg-tokopedia-dark text-white p-2 rounded-xl text-xs font-bold transition shadow-sm" title="+ Keranjang">
                        <i class="fa-solid fa-cart-plus text-sm"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-200">
                <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4 block"></i>
                <h3 class="text-base font-bold text-gray-700">Produk Tidak Ditemukan</h3>
                <p class="text-xs text-gray-400 mt-1">Coba gunakan kata kunci pencarian yang berbeda.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block bg-tokopedia text-white text-xs font-bold px-5 py-2.5 rounded-xl">Lihat Semua Produk</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Interactive Product Quick View Modal -->
    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.outside="modalOpen = false" class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl border border-gray-200 relative overflow-hidden">
            <button @click="modalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl z-10">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <template x-if="selectedProduct">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Image Gallery -->
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border border-gray-200">
                        <img :src="selectedProduct.image_url" :alt="selectedProduct.name" class="w-full h-full object-cover">
                    </div>

                    <!-- Specs & Buy Form -->
                    <div class="flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="bg-tokopedia-light text-tokopedia text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase" x-text="selectedProduct.category ? selectedProduct.category.name : 'Produk'"></span>
                            <h2 class="text-base font-extrabold text-gray-900 leading-snug" x-text="selectedProduct.name"></h2>

                            <!-- Rating & Store -->
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1 text-yellow-500 font-bold"><i class="fa-solid fa-star"></i> <span x-text="selectedProduct.rating"></span></span>
                                <span>•</span>
                                <span class="font-semibold text-tokopedia" x-text="selectedProduct.store_name"></span>
                            </div>

                            <!-- Price -->
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 mt-2">
                                <div class="text-xl font-extrabold text-tokopedia" x-text="formattedPrice"></div>
                                <template x-if="selectedProduct.original_price">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="text-red-600 font-bold bg-red-100 px-1.5 py-0.5 rounded" x-text="selectedProduct.discount_percent + '% OFF'"></span>
                                        <span class="text-gray-400 line-through" x-text="formattedOriginalPrice"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Description -->
                            <div class="text-xs text-gray-600 max-h-32 overflow-y-auto leading-relaxed pt-2">
                                <span class="font-bold text-gray-800 block mb-1">Deskripsi Produk:</span>
                                <p x-text="selectedProduct.description"></p>
                            </div>
                        </div>

                        <!-- Quantity Selector & Actions -->
                        <div class="space-y-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-700">Jumlah Pembelian:</span>
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button @click="modalQty > 1 ? modalQty-- : 1" class="px-3 py-1 font-bold text-gray-600 hover:bg-gray-100">-</button>
                                    <span x-text="modalQty" class="px-3 font-semibold"></span>
                                    <button @click="modalQty++" class="px-3 py-1 font-bold text-gray-600 hover:bg-gray-100">+</button>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button @click="addToCart(selectedProduct.id, modalQty); modalOpen = false"
                                        class="flex-1 bg-tokopedia-light text-tokopedia hover:bg-tokopedia hover:text-white border border-tokopedia font-bold py-3 rounded-xl text-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-cart-plus"></i> + Keranjang
                                </button>
                                <button @click="addToCart(selectedProduct.id, modalQty); window.location.href='{{ route('checkout.index') }}'"
                                        class="flex-1 bg-tokopedia hover:bg-tokopedia-dark text-white font-bold py-3 rounded-xl text-xs transition flex items-center justify-center gap-2 shadow-lg shadow-tokopedia/30">
                                    Beli Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function homeFeed() {
        return {
            activeSlide: 0,
            slides: [
                {
                    title: 'Diskon Spesial Gadget Terbaru s/d 10 Juta',
                    subtitle: 'Beli iPhone 15 Pro, Samsung S24 & Laptop Gaming dengan Garansi Resmi iBox.',
                    tag: 'PROMO SPESIAL',
                    image: 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=1200&q=80'
                },
                {
                    title: 'Fashion Trend 2026 Bebas Ongkir Rp0',
                    subtitle: 'Lengkapi gaya style fashion pria & wanita dari Official Brand pilihan.',
                    tag: 'SUPER SALE',
                    image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&q=80'
                },
                {
                    title: 'Tokopedia NOW! Bahan Segar 15 Menit Tiba',
                    subtitle: 'Sayur, buah, kopi arabika & kebutuhan harian dikirim cepat kilat.',
                    tag: 'FLASH DELIVERY',
                    image: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200&q=80'
                }
            ],
            timer: { hours: '02', minutes: '48', seconds: '35' },
            modalOpen: false,
            selectedProduct: null,
            formattedPrice: '',
            formattedOriginalPrice: '',
            modalQty: 1,

            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            },
            openProductModal(slug) {
                fetch(`/product/${slug}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    this.selectedProduct = data.product;
                    this.formattedPrice = data.formatted_price;
                    this.formattedOriginalPrice = data.formatted_original_price;
                    this.modalQty = 1;
                    this.modalOpen = true;
                });
            }
        }
    }
</script>
@endpush
