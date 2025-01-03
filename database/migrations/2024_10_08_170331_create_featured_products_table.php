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
        Schema::create('featured_products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('description');
            $table->string('name');
            $table->string('slug');
            $table->integer('qty');
            $table->double('price', 10, 2); 
            $table->enum('status', ['active', 'inactive'])->default('inactive'); 
            $table->enum('showHome', ['yes', 'no'])->default('no'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_products');
    }
};
