<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Games
            [
                'name' => 'Cyberpunk Adventure',
                'slug' => 'cyberpunk-adventure',
                'description' => 'Petualangan futuristik yang mendebarkan di dunia cyberpunk. Jelajahi kota neon dengan teknologi canggih dan karakter yang menarik.',
                'price' => 150000,
                'type' => 'game',
                'cover_url' => 'https://via.placeholder.com/400x300/6366f1/ffffff?text=Cyberpunk+Adventure',
                'metadata' => json_encode([
                    'platform' => ['PC', 'Mobile'],
                    'genre' => 'Adventure',
                    'size' => '2.5 GB',
                    'rating' => '4.8/5'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Fantasy RPG Quest',
                'slug' => 'fantasy-rpg-quest',
                'description' => 'Game RPG fantasy dengan dunia terbuka yang luas. Kustomisasi karakter, quest epik, dan sistem combat yang mendalam.',
                'price' => 200000,
                'type' => 'game',
                'cover_url' => 'https://via.placeholder.com/400x300/10b981/ffffff?text=Fantasy+RPG',
                'metadata' => json_encode([
                    'platform' => ['PC', 'Console'],
                    'genre' => 'RPG',
                    'size' => '8.2 GB',
                    'rating' => '4.9/5'
                ]),
                'is_active' => true,
            ],
            
            // E-books
            [
                'name' => 'Panduan Lengkap Laravel 11',
                'slug' => 'panduan-lengkap-laravel-11',
                'description' => 'E-book komprehensif untuk mempelajari Laravel 11 dari dasar hingga advanced. Dilengkapi dengan contoh project dan best practices.',
                'price' => 75000,
                'type' => 'ebook',
                'cover_url' => 'https://via.placeholder.com/400x300/ef4444/ffffff?text=Laravel+11+Guide',
                'metadata' => json_encode([
                    'format' => 'PDF',
                    'pages' => 450,
                    'language' => 'Indonesian',
                    'author' => 'Tech Expert',
                    'updated' => '2024'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Mastering React & Next.js',
                'slug' => 'mastering-react-nextjs',
                'description' => 'Buku digital untuk menguasai React dan Next.js. Pelajari hooks, state management, SSR, dan deployment modern.',
                'price' => 85000,
                'type' => 'ebook',
                'cover_url' => 'https://via.placeholder.com/400x300/3b82f6/ffffff?text=React+NextJS',
                'metadata' => json_encode([
                    'format' => 'PDF + EPUB',
                    'pages' => 380,
                    'language' => 'Indonesian',
                    'author' => 'Frontend Master',
                    'updated' => '2024'
                ]),
                'is_active' => true,
            ],
            
            // Workflows
            [
                'name' => 'Social Media Automation Workflow',
                'slug' => 'social-media-automation-workflow',
                'description' => 'Template workflow untuk otomasi posting social media menggunakan tools seperti Zapier dan Make.com. Hemat waktu hingga 10 jam per minggu.',
                'price' => 125000,
                'type' => 'workflow',
                'cover_url' => 'https://via.placeholder.com/400x300/8b5cf6/ffffff?text=Social+Media+Workflow',
                'metadata' => json_encode([
                    'tools' => ['Zapier', 'Make.com', 'Buffer'],
                    'platforms' => ['Instagram', 'Twitter', 'LinkedIn', 'Facebook'],
                    'setup_time' => '2 hours',
                    'includes' => ['Templates', 'Video Tutorial', 'Support']
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'E-commerce Order Processing Workflow',
                'slug' => 'ecommerce-order-processing-workflow',
                'description' => 'Workflow otomatis untuk memproses pesanan e-commerce dari payment hingga shipping notification. Integrase dengan berbagai platform.',
                'price' => 175000,
                'type' => 'workflow',
                'cover_url' => 'https://via.placeholder.com/400x300/f59e0b/ffffff?text=Ecommerce+Workflow',
                'metadata' => json_encode([
                    'tools' => ['Zapier', 'Shopify', 'WooCommerce'],
                    'features' => ['Auto Invoice', 'Inventory Update', 'Customer Notification'],
                    'setup_time' => '3 hours',
                    'includes' => ['Setup Guide', 'Video Tutorial', '30-day Support']
                ]),
                'is_active' => true,
            ],
            
            // Modules
            [
                'name' => 'Advanced Authentication Module',
                'slug' => 'advanced-authentication-module',
                'description' => 'Module Laravel untuk sistem autentikasi advanced dengan 2FA, social login, dan role management. Siap pakai dan mudah diintegrasikan.',
                'price' => 250000,
                'type' => 'module',
                'cover_url' => 'https://via.placeholder.com/400x300/06b6d4/ffffff?text=Auth+Module',
                'metadata' => json_encode([
                    'framework' => 'Laravel 11',
                    'features' => ['2FA', 'Social Login', 'Role Management', 'API Authentication'],
                    'includes' => ['Source Code', 'Documentation', 'Installation Guide'],
                    'support' => '6 months'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Payment Gateway Integration Module',
                'slug' => 'payment-gateway-integration-module',
                'description' => 'Module PHP untuk integrasi multiple payment gateway (Midtrans, Xendit, PayPal). Mendukung berbagai metode pembayaran.',
                'price' => 300000,
                'type' => 'module',
                'cover_url' => 'https://via.placeholder.com/400x300/10b981/ffffff?text=Payment+Module',
                'metadata' => json_encode([
                    'framework' => 'PHP 8+',
                    'gateways' => ['Midtrans', 'Xendit', 'PayPal', 'Stripe'],
                    'features' => ['Multi Gateway', 'Webhook Handler', 'Transaction Log'],
                    'includes' => ['Source Code', 'API Documentation', 'Test Cases']
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
