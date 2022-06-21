<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRenameNameAndDescriptionInTagDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('tag_descriptions', function (Blueprint $table) {
            $table->renameColumn('localization_name', 'name');
            $table->renameColumn('localization_descriptions', 'description');
        });
    }

    public function down()
    {
        Schema::table('tag_descriptions', function (Blueprint $table) {
            $table->renameColumn('name', 'localization_name');
            $table->renameColumn('description', 'localization_descriptions');
        });
    }
}
