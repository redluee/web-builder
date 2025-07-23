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
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('element_id');
            $table->integer('sort_order');
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'sort_order']);

            $table->foreign('page_id')->references('id')->on('page')->onDelete('cascade');
            $table->foreign('element_id')->references('id')->on('element')->onDelete('cascade');
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
