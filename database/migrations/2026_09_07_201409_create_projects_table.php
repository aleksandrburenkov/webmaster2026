<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('client')->nullable();
            $table->integer('year')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('budget')->nullable();
            $table->integer('duration')->nullable();
            $table->string('accent_theme_color', 7)->nullable();
            $table->json('gallery')->nullable();
            $table->json('services_list')->nullable();
            $table->json('metrics')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};