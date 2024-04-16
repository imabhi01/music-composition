<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compositions', function (Blueprint $table) {
            $table->id();
            $table->string('zoodiac_sign_sun');
            $table->string('zoodiac_sign_moon');
            $table->string('zoodiac_sign_rising');
            $table->string('composed_audio_path');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('compositions');
    }
}
