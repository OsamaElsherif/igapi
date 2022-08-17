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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string("story_id");
            $table->string("media_url");
            $table->string("media_type");
            $table->string("media_product_type");
            $table->string("thumbnail_url");
            // user data
            $table->integer("user_id");
            // report data
            $table->integer("report_id");
            // insight information
            $table->integer("insight_id");
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
        Schema::dropIfExists('stories');
    }
};
