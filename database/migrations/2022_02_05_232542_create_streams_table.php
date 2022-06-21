<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignId('game_id')->nullable()->constrained('games')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('user_login');
            $table->string('user_name');
            $table->string('game_name');
            $table->string('title');
            $table->unsignedBigInteger('viewer_count');
            $table->dateTime('started_at');
            $table->string('language');
            $table->string('thumbnail_url');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('streams');
    }
}
