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
        Schema::create('asy_user_resumes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asy_user_id');
            $table->string('position');
            $table->boolean('position_type')->default(0);
            $table->string('doc_number')->nullable();
            $table->string('doc_date')->nullable();
            $table->string('username');
            $table->string('password');
            $table->tinyInteger('user_status')->default(0);
            $table->string('user_roles')->nullable();
            $table->text('info')->nullable();
            $table->timestamps();

            $table->foreign('asy_user_id')->references('id')->on('asycuda_users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asy_user_resumes');
    }
};
