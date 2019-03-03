<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('doctor_id');
            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('room_id');
            $table->dateTime('start');
            $table->dateTime('end');
            // TODO: move enums to separate tables
            $table->enum('type', ['walk-in', 'annual checkup']);
            $table->enum('status', ['active', 'cancelled', 'complete']);
            $table->timestamps();
        });

        Schema::table('appointments', function($table) {
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function($table) {
            // Drop foreign key 'doctor_id' from 'appointments' table
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['room_id']);
        });

        Schema::dropIfExists('appointments');
    }
}
