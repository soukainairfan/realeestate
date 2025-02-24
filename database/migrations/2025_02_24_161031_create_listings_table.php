<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Deletes listings if user is deleted
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->enum('transaction_type', ['rent', 'sale']);
            $table->string('property_type');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('area', 10, 2);
            $table->string('location');
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
