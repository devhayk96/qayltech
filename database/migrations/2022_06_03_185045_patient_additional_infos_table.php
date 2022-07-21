<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientAdditionalInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_additional_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')->cascadeOnDelete();

            $table->foreignId('workout_id')->nullable()
                ->constrained('patient_workout_infos')->nullOnDelete();

            $table->string('key');
            $table->string('value');
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
        Schema::dropIfExists('patient_additional_infos');
    }
}
