<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->string('address');
            $table->foreignId('category_id')->nullable()
                ->constrained('categories')->nullOnDelete();
            $table->foreignId('country_id')
                ->constrained('countries')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()
                ->constrained('organizations')->cascadeOnDelete();
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
        Schema::table('hospitals', function($table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('hospitals');
    }
}
