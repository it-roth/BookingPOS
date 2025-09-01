<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageUrlToImageInMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename image_url to image in movies table
        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('image_url', 'image');
        });

        // Rename image_url to image in food_items table
        Schema::table('food_items', function (Blueprint $table) {
            $table->renameColumn('image_url', 'image');
        });

        // Rename image_url to image in drinks table
        Schema::table('drinks', function (Blueprint $table) {
            $table->renameColumn('image_url', 'image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert changes for movies table
        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('image', 'image_url');
        });

        // Revert changes for food_items table
        Schema::table('food_items', function (Blueprint $table) {
            $table->renameColumn('image', 'image_url');
        });

        // Revert changes for drinks table
        Schema::table('drinks', function (Blueprint $table) {
            $table->renameColumn('image', 'image_url');
        });
    }
}
