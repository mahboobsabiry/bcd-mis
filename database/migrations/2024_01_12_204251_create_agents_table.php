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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('phone2')->nullable();
            $table->string('id_number')->unique();
            $table->string('address')->nullable();

            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->string('doc_number')->nullable();
            $table->string('company_name')->nullable();
            $table->bigInteger('company_tin')->nullable();

            $table->string('from_date2')->nullable();
            $table->string('to_date2')->nullable();
            $table->string('doc_number2')->nullable();
            $table->string('company_name2')->nullable();
            $table->bigInteger('company_tin2')->nullable();

            $table->string('from_date3')->nullable();
            $table->string('to_date3')->nullable();
            $table->string('doc_number3')->nullable();
            $table->string('company_name3')->nullable();
            $table->bigInteger('company_tin3')->nullable();

            $table->string('signature')->nullable();

            $table->text('background')->nullable();
            $table->text('info')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
