<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('birth_date');
            $table->date('disability_date')->comment('հաշմանդամություն ձեռք բերելու տարեթիվ');
            $table->string('disability_reason')->comment('հաշմանդամություն ձեռք բերելու պատճառ');
            $table->string('disability_category')->comment('հաշմանդամության կարգ');
            $table->string('injury')->comment('վնասվածք');
            $table->date('workout_begin')->nullable()->comment('երբ է ձեռք բերել սարք կամ երբ է սկսել պարապել');
            $table->boolean('is_individual')->comment('անհատական շահառու');
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
