<?php

use App\Shared\Enums\FileStatusEnum;
use App\Shared\Enums\FileTypeEnum;
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
        $fileTypes = FileTypeEnum::list();
        $fileStatus = FileStatusEnum::list();

        Schema::create('files', function (Blueprint $table) use ($fileTypes, $fileStatus) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', $fileTypes);
            $table->string('path');
            $table->enum('status', $fileStatus);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
