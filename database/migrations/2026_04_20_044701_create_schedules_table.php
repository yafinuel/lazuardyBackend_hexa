<?php

use App\Shared\Enums\ScheduleStatusEnum;
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
        $status = ScheduleStatusEnum::list();

        Schema::create('schedules', function (Blueprint $table) use ($status) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors', 'user_id')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students', 'user_id')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->string('reason')->nullable();
            $table->enum('learning_method', ['online', 'offline']);
            $table->string('address');
            $table->enum('status', $status)->default(ScheduleStatusEnum::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
