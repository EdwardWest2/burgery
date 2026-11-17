<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Menu CONTOH (nama, deskripsi, dan harga adalah demonstrasi).
     * Ganti dari Admin > Menu.
     */
    public function run(): void
    {
        $burger = MenuCategory::firstOrCreate(['slug' => 'burger'], ['name' => 'Burger', 'sort_order' => 1]);
        $dessert = MenuCategory::firstOrCreate(['slug' => 'dessert'], ['name' => 'Dessert', 'sort_order' => 2]);

        $items = [
            [$burger, 'classic-double-burger', 'Classic Double Burger', 'Burger dengan dua patty daging, keju leleh, selada, tomat, dan saus di antara roti yang dipanggang.', 52000, 'assets/img/menu/1.jpg', 'Classic Double Burger dengan dua patty dan keju', true, 1],
            [$burger, 'crispy-chicken-burger', 'Crispy Chicken Burger', 'Burger ayam krispi dengan saus creamy dan topping segar, disajikan di atas roti lembut.', 46000, 'assets/img/menu/3.jpg', 'Crispy Chicken Burger dengan saus creamy', true, 2],
            [$burger, 'burger-fries-combo', 'Burger & Fries Combo', 'Burger pilihan dengan kentang goreng renyah di sampingnya.', 58000, 'assets/img/off-img.jpg', 'Burger dengan kentang goreng di atas talenan kayu', true, 3],
            [$burger, 'classic-cheeseburger', 'Classic Cheeseburger', 'Cheeseburger klasik dengan patty daging, keju, selada, dan tomat.', 42000, 'assets/img/portfolio/work1.jpg', 'Classic Cheeseburger dengan selada dan tomat', false, 4],
            [$dessert, 'donat-sprinkle', 'Donat Sprinkle', 'Donat dengan glaze dan taburan warna-warni sebagai penutup santapan.', 22000, 'assets/img/menu/5.jpg', 'Tumpukan donat dengan taburan sprinkle', false, 5],
        ];

        foreach ($items as [$category, $slug, $name, $description, $price, $image, $alt, $featured, $order]) {
            MenuItem::firstOrCreate(['slug' => $slug], [
                'menu_category_id' => $category->id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'image_alt' => $alt,
                'is_available' => true,
                'is_featured' => $featured,
                'sort_order' => $order,
            ]);
        }
    }
}
