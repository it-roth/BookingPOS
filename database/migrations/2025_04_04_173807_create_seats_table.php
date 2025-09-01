<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->onDelete('cascade');
            $table->string('row');
            $table->integer('number');
            $table->string('type')->default('regular'); // regular, premium, vip
            $table->boolean('is_available')->default(true);
            $table->decimal('additional_charge', 8, 2)->default(0.00);
            $table->timestamps();
            
            // Add unique constraint for row and number within a hall
            $table->unique(['hall_id', 'row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
