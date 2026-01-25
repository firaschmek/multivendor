<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@multivendor.tn',
            'password' => Hash::make('password'),
            'phone' => '+216 20 123 456',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Test Vendor Users
        $vendor1User = User::create([
            'name' => 'محمد التونسي',
            'email' => 'vendor1@multivendor.tn',
            'password' => Hash::make('password'),
            'phone' => '+216 20 234 567',
            'role' => 'vendor',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $vendor2User = User::create([
            'name' => 'فاطمة الصحراوي',
            'email' => 'vendor2@multivendor.tn',
            'password' => Hash::make('password'),
            'phone' => '+216 20 345 678',
            'role' => 'vendor',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Test Customer
        $customer = User::create([
            'name' => 'أحمد بن علي',
            'email' => 'customer@multivendor.tn',
            'password' => Hash::make('password'),
            'phone' => '+216 20 456 789',
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Vendors
        $vendor1 = Vendor::create([
            'user_id' => $vendor1User->id,
            'shop_name' => 'Tech Store',
            'shop_name_ar' => 'متجر التقنية',
            'slug' => 'tech-store',
            'description' => 'Best electronics and gadgets in Tunisia',
            'description_ar' => 'أفضل الإلكترونيات والأدوات في تونس',
            'phone' => '+216 20 234 567',
            'email' => 'vendor1@multivendor.tn',
            'address' => 'Avenue Habib Bourguiba, Tunis',
            'address_ar' => 'شارع الحبيب بورقيبة، تونس',
            'commission_rate' => 10.00,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $vendor2 = Vendor::create([
            'user_id' => $vendor2User->id,
            'shop_name' => 'Fashion Hub',
            'shop_name_ar' => 'مركز الموضة',
            'slug' => 'fashion-hub',
            'description' => 'Latest fashion trends and clothing',
            'description_ar' => 'أحدث صيحات الموضة والملابس',
            'phone' => '+216 20 345 678',
            'email' => 'vendor2@multivendor.tn',
            'address' => 'Centre Ville, Tunis',
            'address_ar' => 'وسط المدينة، تونس',
            'commission_rate' => 12.00,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Create Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'name_ar' => 'إلكترونيات',
            'slug' => 'electronics',
            'description' => 'Electronic devices and gadgets',
            'description_ar' => 'الأجهزة الإلكترونية والأدوات',
            'order' => 1,
            'is_active' => true,
        ]);

        $phones = Category::create([
            'parent_id' => $electronics->id,
            'name' => 'Phones',
            'name_ar' => 'هواتف',
            'slug' => 'phones',
            'description' => 'Mobile phones and smartphones',
            'description_ar' => 'الهواتف المحمولة والهواتف الذكية',
            'order' => 1,
            'is_active' => true,
        ]);

        $laptops = Category::create([
            'parent_id' => $electronics->id,
            'name' => 'Laptops',
            'name_ar' => 'حواسيب محمولة',
            'slug' => 'laptops',
            'description' => 'Laptops and notebooks',
            'description_ar' => 'الحواسيب المحمولة والدفترية',
            'order' => 2,
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'name_ar' => 'أزياء',
            'slug' => 'fashion',
            'description' => 'Clothing and accessories',
            'description_ar' => 'الملابس والإكسسوارات',
            'order' => 2,
            'is_active' => true,
        ]);

        $menFashion = Category::create([
            'parent_id' => $fashion->id,
            'name' => "Men's Fashion",
            'name_ar' => 'أزياء رجالية',
            'slug' => 'mens-fashion',
            'description' => 'Fashion for men',
            'description_ar' => 'الأزياء للرجال',
            'order' => 1,
            'is_active' => true,
        ]);

        $womenFashion = Category::create([
            'parent_id' => $fashion->id,
            'name' => "Women's Fashion",
            'name_ar' => 'أزياء نسائية',
            'slug' => 'womens-fashion',
            'description' => 'Fashion for women',
            'description_ar' => 'الأزياء للنساء',
            'order' => 2,
            'is_active' => true,
        ]);

        // Create Sample Products
        Product::create([
            'vendor_id' => $vendor1->id,
            'category_id' => $phones->id,
            'name' => 'Samsung Galaxy S24',
            'name_ar' => 'سامسونج جالاكسي S24',
            'slug' => 'samsung-galaxy-s24',
            'description' => 'Latest Samsung flagship smartphone with advanced features',
            'description_ar' => 'أحدث هاتف ذكي رائد من سامسونج مع ميزات متقدمة',
            'sku' => 'PHONE-001',
            'price' => 2499.00,
            'compare_price' => 2799.00,
            'cost' => 2000.00,
            'quantity' => 50,
            'low_stock_threshold' => 5,
            'track_inventory' => true,
            'stock_status' => 'in_stock',
            'weight' => 0.2,
            'is_active' => true,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Product::create([
            'vendor_id' => $vendor1->id,
            'category_id' => $laptops->id,
            'name' => 'Dell XPS 15',
            'name_ar' => 'ديل XPS 15',
            'slug' => 'dell-xps-15',
            'description' => 'Powerful laptop for professionals',
            'description_ar' => 'حاسوب محمول قوي للمحترفين',
            'sku' => 'LAPTOP-001',
            'price' => 4999.00,
            'compare_price' => 5499.00,
            'cost' => 4200.00,
            'quantity' => 20,
            'low_stock_threshold' => 3,
            'track_inventory' => true,
            'stock_status' => 'in_stock',
            'weight' => 2.0,
            'is_active' => true,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Product::create([
            'vendor_id' => $vendor2->id,
            'category_id' => $menFashion->id,
            'name' => 'Classic Leather Jacket',
            'name_ar' => 'جاكيت جلدي كلاسيكي',
            'slug' => 'classic-leather-jacket',
            'description' => 'Premium quality leather jacket',
            'description_ar' => 'جاكيت جلدي عالي الجودة',
            'sku' => 'FASHION-001',
            'price' => 450.00,
            'compare_price' => 599.00,
            'cost' => 300.00,
            'quantity' => 30,
            'low_stock_threshold' => 5,
            'track_inventory' => true,
            'stock_status' => 'in_stock',
            'weight' => 1.5,
            'is_active' => true,
            'is_featured' => false,
            'published_at' => now(),
        ]);

        Product::create([
            'vendor_id' => $vendor2->id,
            'category_id' => $womenFashion->id,
            'name' => 'Elegant Summer Dress',
            'name_ar' => 'فستان صيفي أنيق',
            'slug' => 'elegant-summer-dress',
            'description' => 'Beautiful summer dress for all occasions',
            'description_ar' => 'فستان صيفي جميل لجميع المناسبات',
            'sku' => 'FASHION-002',
            'price' => 180.00,
            'compare_price' => 250.00,
            'cost' => 120.00,
            'quantity' => 45,
            'low_stock_threshold' => 10,
            'track_inventory' => true,
            'stock_status' => 'in_stock',
            'weight' => 0.4,
            'is_active' => true,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Default Login Credentials:');
        $this->command->info('=========================');
        $this->command->info('Admin: admin@multivendor.tn / password');
        $this->command->info('Vendor 1: vendor1@multivendor.tn / password');
        $this->command->info('Vendor 2: vendor2@multivendor.tn / password');
        $this->command->info('Customer: customer@multivendor.tn / password');
    }
}
