<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'icon' => 'fa-solid fa-tv',
                'image' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=500&q=80',
                'badge' => 'Promo Top',
            ],
            [
                'name' => 'Handphone & Tablet',
                'slug' => 'handphone-tablet',
                'icon' => 'fa-solid fa-mobile-screen-button',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80',
                'badge' => 'Flash Sale',
            ],
            [
                'name' => 'Fashion Pria',
                'slug' => 'fashion-pria',
                'icon' => 'fa-solid fa-shirt',
                'image' => 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?w=500&q=80',
                'badge' => 'Trend 2026',
            ],
            [
                'name' => 'Fashion Wanita',
                'slug' => 'fashion-wanita',
                'icon' => 'fa-solid fa-person-dress',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80',
                'badge' => 'Diskon 70%',
            ],
            [
                'name' => 'Komputer & Laptop',
                'slug' => 'komputer-laptop',
                'icon' => 'fa-solid fa-laptop',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&q=80',
                'badge' => 'Official',
            ],
            [
                'name' => 'Otomotif',
                'slug' => 'otomotif',
                'icon' => 'fa-solid fa-motorcycle',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=500&q=80',
                'badge' => 'Bebas Ongkir',
            ],
            [
                'name' => 'Rumah Tangga',
                'slug' => 'rumah-tangga',
                'icon' => 'fa-solid fa-house-laptop',
                'image' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=500&q=80',
                'badge' => 'Pilihan',
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'icon' => 'fa-solid fa-utensils',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&q=80',
                'badge' => 'NOW Fresh',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
