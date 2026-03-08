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

        for ($i=1; $i<=12; $i++){
            $classes[] = ['name' => 'kelas ' . $i];
        }
        $classes[] = ['umum' => 'umum'];

        ClassModel::insert($classes);
    }
}
