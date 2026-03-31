<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket 4 Sesi',
                'session' => 4,
                'price' => 400000,
                'description' => 'Paket belajar dengan 4 sesi pertemuan.',
                // 'discount' => 0.1,
                // 'image_path' => 'packages/paket-4-sesi.jpg',
            ],
            [
                'name' => 'Paket 8 Sesi',
                'session' => 8,
                'price' => 750000,
                'description' => 'Paket belajar dengan 8 sesi pertemuan.',
                // 'discount' => 0.15,
                // 'image_path' => 'packages/paket-8-sesi.jpg',
            ],
            [
                'name' => 'Paket 12 Sesi',
                'session' => 12,
                'price' => 1050000,
                'description' => 'Paket belajar dengan 12 sesi pertemuan.',
                // 'discount' => 0.2,
                // 'image_path' => 'packages/paket-12-sesi.jpg',
            ],
        ];
        
        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
