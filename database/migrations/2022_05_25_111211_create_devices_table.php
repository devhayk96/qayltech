<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('country_id')->nullable()
                ->constrained('countries')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()
                ->constrained('organizations')->nullOnDelete();
            $table->foreignId('hospital_id')->nullable()
                ->constrained('hospitals')->nullOnDelete();
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
        Schema::table('devices', function($table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('devices');
    }
}
