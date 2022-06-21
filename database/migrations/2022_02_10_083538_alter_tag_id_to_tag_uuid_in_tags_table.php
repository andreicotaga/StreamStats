<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTagIdToTagUuidInTagsTable extends Migration
{
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('tag_id', 'tag_uuid');
        });
    }

    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->renameColumn('tag_uuid', 'tag_id');
        });
    }
}
