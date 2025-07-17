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
        Schema::create('tuners', function (Blueprint $table) {
            $table->id();

            $table->string('nickname');
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('language', 2)->default("en");
            $table->string('portale')->nullable();
            $table->string('profession')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuners');
    }
};