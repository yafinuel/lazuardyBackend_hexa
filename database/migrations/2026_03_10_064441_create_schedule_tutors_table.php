<?php

use App\shared\enums\DayEnum;
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
        $days = DayEnum::list();

        Schema::create('schedule_tutors', function (Blueprint $table) use ($days) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors', 'user_id')->cascadeOnDelete();
            $table->enum('day', $days);
            $table->time('time');

            $table->unique(['tutor_id', 'day', 'time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_tutors');
    }
};
