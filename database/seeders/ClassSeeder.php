<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [];

        for ($i=1; $i<=6; $i++){
            $classes[] = [
                'name' => 'kelas ' . $i, 
                'jenjang' => 'SD'
            ];
        }
        for ($i=0; $i<3; $i++){
            $n = $i + 7;
            $classes[] = [
                'name' => 'kelas ' . $n, 
                'jenjang' => 'SMP'
            ];
        }
        for ($i=0; $i<3; $i++){
            $n = $i + 10;
            $classes[] = [
                'name' => 'kelas ' . $n, 
                'jenjang' => 'SMA'
            ];
        }
        
        $classes[] = [
            'name' => 'umum',
            'jenjang' => 'umum'
        ];

        ClassModel::insert($classes);
    }
}
