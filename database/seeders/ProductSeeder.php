<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $products = [
            'Medicines' => [
                ['name' => 'Biogesic Paracetamol 500mg',     'price' => 5.50,    'featured' => true],
                ['name' => 'Neozep Forte Tablet',             'price' => 8.75,    'featured' => false],
                ['name' => 'Solmux Carbocisteine 500mg',      'price' => 12.00,   'featured' => false],
                ['name' => 'Kremil-S Antacid Tablet',         'price' => 6.25,    'featured' => false],
                ['name' => 'Decolgen No-Drowse Tablet',       'price' => 9.00,    'featured' => false],
                ['name' => 'Imodium Loperamide 2mg',          'price' => 14.50,   'featured' => false],
                ['name' => 'Mefenamic Acid 500mg Capsule',    'price' => 7.00,    'featured' => false],
                ['name' => 'Amoxicillin 500mg Capsule',       'price' => 11.25,   'featured' => false],
                ['name' => 'Cetirizine Hydrochloride 10mg',   'price' => 6.75,    'featured' => false],
            ],
            'Personal Care' => [
                ['name' => 'Cetaphil Gentle Skin Cleanser 500ml',    'price' => 399.00, 'featured' => true],
                ['name' => 'Neutrogena Hydro Boost Water Gel',        'price' => 549.00, 'featured' => false],
                ['name' => 'Dove Sensitive Skin Bar Soap',            'price' => 75.00,  'featured' => false],
                ['name' => 'Head & Shoulders Classic Clean Shampoo',  'price' => 189.00, 'featured' => false],
                ['name' => 'Colgate Optic White Toothpaste',          'price' => 99.00,  'featured' => false],
                ['name' => 'Gillette Mach3 Razor',                    'price' => 250.00, 'featured' => false],
                ['name' => 'Vaseline Intensive Care Body Lotion',     'price' => 145.00, 'featured' => false],
                ['name' => 'Listerine Cool Mint Mouthwash 500ml',     'price' => 215.00, 'featured' => false],
                ['name' => 'Kojie San Skin Lightening Soap',          'price' => 55.00,  'featured' => false],
            ],
            'Baby Care' => [
                ['name' => "Johnson's Baby Shampoo 500ml",    'price' => 189.00, 'featured' => true],
                ['name' => 'Pampers Baby Dry Newborn Diapers','price' => 499.00, 'featured' => false],
                ['name' => 'Cerelac Infant Cereal Rice 250g', 'price' => 215.00, 'featured' => false],
                ['name' => 'Enfamil Newborn Infant Formula',  'price' => 899.00, 'featured' => false],
                ['name' => 'Pigeon Baby Bottle 240ml',        'price' => 350.00, 'featured' => false],
                ['name' => 'Mustela Gentle Cleansing Gel',    'price' => 449.00, 'featured' => false],
                ['name' => 'Baby Dove Rich Moisture Lotion',  'price' => 175.00, 'featured' => false],
                ['name' => "Gripe Water Woodward's 150ml",    'price' => 120.00, 'featured' => false],
                ['name' => 'Infantcare Baby Powder 200g',     'price' => 85.00,  'featured' => false],
            ],
            'Wellness' => [
                ['name' => 'Ensure Gold Vanilla 850g',        'price' => 1299.00, 'featured' => true],
                ['name' => 'Myra E 400 IU Capsule',           'price' => 18.00,   'featured' => false],
                ['name' => 'Conzace Multivitamins Capsule',   'price' => 22.50,   'featured' => false],
                ['name' => 'Stresstabs 600 with Zinc',        'price' => 19.75,   'featured' => false],
                ['name' => 'Potassium Chloride 600mg Tablet', 'price' => 8.50,    'featured' => false],
                ['name' => 'Omega-3 Fish Oil 1000mg Softgel', 'price' => 14.00,   'featured' => false],
                ['name' => 'Melatonin 5mg Sleep Aid',         'price' => 16.25,   'featured' => false],
                ['name' => 'Turmeric Curcumin 500mg Capsule', 'price' => 20.00,   'featured' => false],
                ['name' => 'Collagen Plus Vitamin C Tablet',  'price' => 25.50,   'featured' => false],
            ],
            'Vitamins' => [
                ['name' => 'Berocca Performance Effervescent Tablet', 'price' => 45.00,  'featured' => true],
                ['name' => 'Vitamin C 1000mg Ascorbic Acid Tablet',   'price' => 12.00,  'featured' => false],
                ['name' => 'Vitamin D3 5000 IU Softgel',              'price' => 18.50,  'featured' => false],
                ['name' => 'Centrum Adult Multivitamins Tablet',      'price' => 28.00,  'featured' => false],
                ['name' => 'Cherifer Syrup with Zinc 120ml',          'price' => 185.00, 'featured' => false],
                ['name' => 'B-Complex with Iron Capsule',             'price' => 9.75,   'featured' => false],
                ['name' => 'Zinc Sulfate 20mg Tablet',                'price' => 7.50,   'featured' => false],
                ['name' => 'Folic Acid 400mcg Tablet',                'price' => 5.25,   'featured' => false],
                ['name' => 'Biotin 5000mcg Hair & Nail Supplement',   'price' => 22.00,  'featured' => false],
            ],
            'First Aid' => [
                ['name' => 'Betadine Antiseptic Solution 60ml',      'price' => 75.00,  'featured' => true],
                ['name' => "Johnson's First Aid Alcohol 70% 500ml",  'price' => 89.00,  'featured' => false],
                ['name' => 'Band-Aid Flexible Fabric Bandages 30s',  'price' => 120.00, 'featured' => false],
                ['name' => 'Cotton Balls Absorbent 100s',            'price' => 45.00,  'featured' => false],
                ['name' => 'Micropore Medical Tape 1 inch',          'price' => 55.00,  'featured' => false],
                ['name' => 'ACE Elastic Bandage 3 inch',             'price' => 135.00, 'featured' => false],
                ['name' => 'Omron Digital Thermometer',              'price' => 399.00, 'featured' => false],
                ['name' => 'Surgipack Sterile Gauze Pad 4x4',        'price' => 35.00,  'featured' => false],
                ['name' => 'Bacitracin Zinc Antibiotic Ointment',    'price' => 95.00,  'featured' => false],
            ],
        ];

        foreach ($products as $category => $items) {
            foreach ($items as $item) {
                DB::table('products')->insert([
                    'name'        => $item['name'],
                    'category'    => $category,
                    'price'       => $item['price'],
                    'old_price'   => null,
                    'rating'      => rand(30, 50) / 10,
                    'image'       => null,
                    'stock'       => rand(5, 50),
                    'is_featured' => $item['featured'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }
    }
}
