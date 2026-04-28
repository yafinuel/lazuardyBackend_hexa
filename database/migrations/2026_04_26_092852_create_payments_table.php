<?php

use App\Shared\Enums\PaymentStatusEnum;
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
        $status = PaymentStatusEnum::list();

        Schema::create('payments', function (Blueprint $table) use ($status){
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('external_id')->unique();
            $table->string('xendit_id')->nullable()->unique();
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->integer('amount');
            $table->enum('status', $status);
            $table->string('checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payload_raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
