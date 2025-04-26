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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('age')->nullable();
            $table->string('password');
            $table->enum('type', ['user', 'vendor', 'admin'])->default('user');
            $table->enum('login_type', ['google', 'apple', 'facebook', 'normal'])->default('normal');
            $table->string('image')->nullable();
            $table->string('fcm')->nullable();
            $table->string('code')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('note')->nullable();
            $table->string('wallet')->default(0);
            $table->boolean('status')->default(true);
            $table->string('invitation_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('country_id')->constrained("countries")->cascadeOnDelete();
            $table->foreignId('city_id')->constrained("cities")->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
