<?php

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

        Schema::create('tutors', function (Blueprint $table) use ($statusses) {
            $table->foreignId('user_id')->constrained('users');
            $table->json('education')->nullable();
            $table->integer('salary')->default(0);
            $table->integer('price')->default(0);
            $table->longText('description')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->json('learning_method')->nullable();
            $table->timestamp('sanction')->nullable();
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
