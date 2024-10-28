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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignUuid('user_id');
            $table->string('content')->nullable();
            $table->string('post_image')->nullable();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->timestamps(); 
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     
        Schema::table('posts', function (Blueprint $table) {
            $table->dropTimestamps();
        
        });
    }
};
