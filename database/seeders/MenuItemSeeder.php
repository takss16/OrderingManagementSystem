<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menuData = [
            'Appetizers' => [
                ['Spring Rolls', 'Crispy vegetable spring rolls.', 80],
                ['Garlic Bread', 'Toasted garlic butter bread.', 60],
                ['Cheese Sticks', 'Fried mozzarella cheese sticks.', 90],
                ['Onion Rings', 'Crispy battered onion rings.', 70],
                ['Buffalo Wings', 'Spicy buffalo chicken wings.', 120],
            ],
            'Main Course' => [
                ['Grilled Chicken', 'Juicy grilled chicken breast.', 180],
                ['Beef Steak', 'Tender beef steak with sauce.', 220],
                ['Pork BBQ', 'Filipino-style pork barbecue.', 160],
                ['Fried Rice Combo', 'Fried rice with egg and meat.', 140],
                ['Spaghetti', 'Classic Pinoy-style spaghetti.', 130],
            ],
            'Desserts' => [
                ['Leche Flan', 'Creamy caramel custard.', 60],
                ['Halo-Halo', 'Shaved ice with mixed sweets.', 95],
                ['Chocolate Cake', 'Moist chocolate layer cake.', 85],
                ['Ice Cream Sundae', 'Vanilla ice cream with toppings.', 75],
                ['Fruit Salad', 'Mixed fruit with cream.', 70],
            ],
            'Drinks' => [
                ['Iced Tea', 'Chilled lemon iced tea.', 40],
                ['Soft Drinks', 'Assorted sodas.', 35],
                ['Mango Shake', 'Fresh mango smoothie.', 55],
                ['Bottled Water', 'Mineral water.', 25],
                ['Coffee', 'Hot brewed coffee.', 45],
            ],
        ];

        foreach ($menuData as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($items as [$name, $desc, $price]) {
                MenuItem::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $desc,
                    'price' => $price,
                    'image' => null,
                    'available' => true,
                ]);
            }
        }
    }
}
