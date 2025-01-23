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
        Schema::create('pt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pt_id');
            $table->string('good_name');
            $table->bigInteger('hs_code');
            $table->bigInteger('total_packages');
            $table->double('weight');
            $table->tinyInteger('status')->default(0);
            $table->text('info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_items');
    }
};
