<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type'); // e.g., image/jpeg, video/mp4, application/pdf
            $table->string('category'); // e.g., 'Gallery', 'Floor Plan', 'Brochure'
            $table->boolean('is_featured')->default(false); // Thumbnail image
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('property_media');
    }
};