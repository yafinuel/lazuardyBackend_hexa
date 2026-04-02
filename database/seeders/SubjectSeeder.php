<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sdClassId = ClassModel::where('level', 'SD')->orderBy('id')->value('id');
        $smpClassId = ClassModel::where('level', 'SMP')->orderBy('id')->value('id');
        $smaClassId = ClassModel::where('level', 'SMA')->orderBy('id')->value('id');

        if (! $sdClassId || ! $smpClassId || ! $smaClassId) {
            return;
        }

        // Default: Kurikulum Merdeka (ID 2)
        $subjects = [
            // Matematika
            ['name' => 'Matematika', 'class_id' => $sdClassId],
            ['name' => 'Matematika', 'class_id' => $smpClassId],
            ['name' => 'Matematika', 'class_id' => $smaClassId],
            
            // Bahasa Inggris
            ['name' => 'Bahasa Inggris', 'class_id' => $sdClassId],
            ['name' => 'Bahasa Inggris', 'class_id' => $smpClassId],
            ['name' => 'Bahasa Inggris', 'class_id' => $smaClassId],
            
            // Fisika
            ['name' => 'Fisika', 'class_id' => $smpClassId],
            ['name' => 'Fisika', 'class_id' => $smaClassId],
            
            // Kimia
            ['name' => 'Kimia', 'class_id' => $smpClassId],
            ['name' => 'Kimia', 'class_id' => $smaClassId],
            
            // Biologi
            ['name' => 'Biologi', 'class_id' => $smpClassId],
            ['name' => 'Biologi', 'class_id' => $smaClassId],
            
            // Ekonomi
            ['name' => 'Ekonomi', 'class_id' => $smpClassId],
            ['name' => 'Ekonomi', 'class_id' => $smaClassId],
            
            // TIK/Komputer
            ['name' => 'TIK/Komputer', 'class_id' => $smaClassId],
            
            // Bahasa Indonesia
            ['name' => 'Bahasa Indonesia', 'class_id' => $sdClassId],
            ['name' => 'Bahasa Indonesia', 'class_id' => $smpClassId],
            ['name' => 'Bahasa Indonesia', 'class_id' => $smaClassId],
            
            // IPA Terpadu
            ['name' => 'IPA Terpadu', 'class_id' => $sdClassId],
            
            // IPS
            ['name' => 'IPS', 'class_id' => $sdClassId],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
