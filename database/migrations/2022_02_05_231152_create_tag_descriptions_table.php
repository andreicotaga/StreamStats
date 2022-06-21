<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('tag_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->string('localization')->index();
            $table->string('localization_name');
            $table->string('localization_descriptions');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tag_descriptions');
    }
}
