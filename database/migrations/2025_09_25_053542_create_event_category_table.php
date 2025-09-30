<?php

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
        Schema::create('event_category', function (Blueprint $table) {
            $table->id();
            // create foreignID column for event table, and set deletion condition
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); 
            // create foreignID column for event table, and set deletion condition
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Make sure the same combination of event and category ids can't be added more than once to the event_category table
            $table->unique(['event_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_category');
    }
};
