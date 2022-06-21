<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamTagTable extends Migration
{
    public function up()
    {
        Schema::create('stream_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('stream_id')->constrained('streams')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stream_tag');
    }
}
