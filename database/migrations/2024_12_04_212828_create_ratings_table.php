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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User who gives the rating
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade'); // Vendor who receives the rating (from users table)
            $table->integer('rating')->unsigned(); // Rating value, e.g., 1 to 5
            $table->text('review')->nullable(); // Optional review text
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
