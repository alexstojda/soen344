<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentAvailabilityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_availability', function (Blueprint $table) {
            $table->primary(['appointment_id', 'availability_id']);
            //$table->increments('id');
            $table->unsignedInteger('appointment_id')->index();
            $table->unsignedInteger('availability_id')->index();
            //$table->unique(['appointment_id', 'availability_id']);

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('appointment_availability');
    }
}
