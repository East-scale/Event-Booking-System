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
        Schema::create('events', function (Blueprint $table) {
            # List of columns to be created, including unique id
            $table->id();
            $table->string('title', 100);
            $table->text('description')->nullable(); // can be empt
            $table->dateTime('event_date');
            $table->time('event_time'); //Add this line
            $table->string('location', 255);
            $table->integer('capacity');
            // foreignID means foreign key, constrained links to users table, and 
            // onDelete means delete all events of a user if user is deleted
            $table->foreignId('organiser_id')->constrained('users')->onDelete('cascade');
            $table->timestamps(); // adds created at and updated at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events'); // delete events tab
    }
};
