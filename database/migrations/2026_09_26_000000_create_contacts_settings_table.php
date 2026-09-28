<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('max, email, vk, whatsapp, site, other');
            $table->string('label');
            $table->string('icon_file')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('url')->nullable();
            $table->string('value')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('block')->comment('contacts, footer');
            $table->json('custom_attributes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts_settings');
    }
};