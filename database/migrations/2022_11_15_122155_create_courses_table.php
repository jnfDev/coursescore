<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            // Foreign Columns --------------------------- 
            $table->foreignId('user_id')->constrained();
            // $table->foreignId('source_id')->constrained();
            //-------------------------------------------- 
            $table->string('name');
            $table->text('description');
            $table->string('url');
            $table->unsignedTinyInteger('score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
