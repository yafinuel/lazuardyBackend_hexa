<?php

use App\Shared\Enums\PayoutStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        $status = PayoutStatusEnum::list();

        Schema::create('payouts', function (Blueprint $table) use ($status) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors', 'user_id')->onDelete('cascade');
            $table->string('payout_number')->unique();
            $table->string('xendit_id')->nullable()->unique();
            $table->integer('amount')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('status', $status)->default(PayoutStatusEnum::REQUESTED->value);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->json('payload_raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
