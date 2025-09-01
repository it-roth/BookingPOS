<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ForceRemovePriceFromMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all current movies
        $movies = DB::table('movies')->get();
        
        // Drop the table
        Schema::dropIfExists('movies');
        
        // Recreate the table without the price column
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('genre');
            $table->integer('duration'); // in minutes
            $table->string('image')->nullable();
            $table->date('release_date');
            $table->boolean('is_showing')->default(true);
            $table->timestamps();
        });
        
        // Reinsert the movies
        foreach ($movies as $movie) {
            DB::table('movies')->insert([
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description,
                'genre' => $movie->genre,
                'duration' => $movie->duration,
                'image' => $movie->image ?? null,
                'release_date' => $movie->release_date,
                'is_showing' => $movie->is_showing,
                'created_at' => $movie->created_at,
                'updated_at' => $movie->updated_at
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add price column back - we won't recover the data
        Schema::table('movies', function (Blueprint $table) {
            $table->decimal('price', 8, 2);
        });
    }
}
