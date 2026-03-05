<?php

use App\Shared\Enums\CourseModeEnum;
use App\Shared\Enums\TutorStatusEnum;
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
        // Membuat data enum
        $statusses = TutorStatusEnum::list();
        $courseMode = CourseModeEnum::list();

        Schema::create('tutors', function (Blueprint $table) use ($statusses, $courseMode) {
            $table->foreignId('user_id')->constrained('users');
            $table->json('education')->nullable();
            $table->integer('salary')->default(0);
            $table->integer('price')->default(0);
            $table->longText('description')->nullable();
            $table->longText('learning_method')->nullable();
            $table->json('qualification')->nullable();
            $table->longText('experience')->nullable();
            $table->json('organization')->nullable();
            $table->enum('badge', [''])->nullable();
            $table->string('bank')->nullable();
            $table->string('rekening')->nullable();
            $table->enum('course_mode', $courseMode)->nullable();
            $table->integer('sanction_amount')->default(0);
            $table->enum('status', $statusses)->nullable();
            $table->timestamps();

            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
