<?php

use App\shared\enums\TutorSubjectEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $status = TutorSubjectEnum::list();
        
        Schema::create('tutor_subjects', function (Blueprint $table) use ($status) {
            $table->foreignId('tutor_id')->constrained('tutors', 'user_id')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('status', $status);
            $table->primary(['tutor_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_subjects');
    }
};
