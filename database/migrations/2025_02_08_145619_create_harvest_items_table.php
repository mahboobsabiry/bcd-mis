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
        Schema::create('harvest_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('harvest_id');
            $table->string('good_name');
            $table->bigInteger('hs_code');
            $table->bigInteger('total_packages');
            $table->double('weight');
            $table->tinyInteger('status')->default(1);
            $table->text('info')->nullable();
            $table->timestamps();

            $table->foreign('harvest_id')->references('id')->on('harvests')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_items');
    }
};
