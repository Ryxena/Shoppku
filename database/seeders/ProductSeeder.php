<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product1 = Product::create([
            'category_id' => 1,
            'name' => 'Smartphone XYZ',
            'description' => 'Latest smartphone with amazing features',
            'stock' => 50,
            'price' => 599.99,
        ]);

        $product1Images = [
            ['image_path' => 'public/products/smartphone-1.jpeg'],
            ['image_path' => 'public/products/smartphone-2.jpeg'],
            ['image_path' => 'public/products/smartphone-3.jpeg'],
        ];
        foreach ($product1Images as $image) {
            ProductImage::create([
                'product_id' => $product1->id,
                'image_path' => $image['image_path'],
            ]);
        }

        $product2 = Product::create([
            'category_id' => 2,
            'name' => 'Classic T-Shirt',
            'description' => 'Comfortable cotton t-shirt',
            'stock' => 100,
            'price' => 29.99,
        ]);

        $product2Images = [
            ['image_path' => 'public/products/tshirt-1.jpeg'],
            ['image_path' => 'public/products/tshirt-2.jpeg'],
        ];
        foreach ($product2Images as $image) {
            ProductImage::create([
                'product_id' => $product2->id,
                'image_path' => $image['image_path'],
            ]);
        }

        $product3 = Product::create([
            'category_id' => 3,
            'name' => 'Programming Guide',
            'description' => 'Complete guide to modern programming',
            'stock' => 30,
            'price' => 49.99,
        ]);

        ProductImage::create([
            'product_id' => $product3->id,
            'image_path' => 'public/products/book-1.jpeg',
        ]);
    }
}
