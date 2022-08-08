<?php

use App\Enums\WorkoutStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientWorkoutInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_workout_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();

            $table->enum('status', WorkoutStatuses::ALL);
            $table->string('game')->nullable();
            $table->string('walk_count')->nullable();
            $table->string('steps_count')->nullable();
            $table->string('steps_opening')->nullable();
            $table->string('speed')->nullable();
            $table->string('passed_way')->nullable();
            $table->string('calories')->nullable();
            $table->string('spent_time')->nullable();
            $table->string('key1')->nullable();
            $table->string('key2')->nullable();
            $table->string('key3')->nullable();
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
        Schema::dropIfExists('patient_workout_infos');
    }
}
