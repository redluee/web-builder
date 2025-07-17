<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Constraint\Constraint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('page')->onDelete('cascade');
            $table->foreignId('element_id')->constrained('element')->onDelete('cascade');
            $table->integer('sort_order');
            $table->json('settings');
            $table->timestamps();

            $table->unique(['page_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_elements');
    }
};
