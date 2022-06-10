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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('birth_date');
            $table->date('disability_date')->nullable()->comment('հաշմանդամություն ձեռք բերելու տարեթիվ');
            $table->string('disability_reason')->nullable()->comment('հաշմանդամություն ձեռք բերելու պատճառ');
            $table->string('disability_category')->nullable()->comment('հաշմանդամության կարգ');
            $table->string('injury')->nullable()->comment('վնասվածք');
            $table->date('workout_begin')->nullable()->comment('երբ է ձեռք բերել սարք կամ երբ է սկսել պարապել');
            $table->boolean('is_individual')->comment('անհատական շահառու');
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function($table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('patients');
    }
}
