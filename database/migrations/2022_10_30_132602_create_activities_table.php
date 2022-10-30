<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->date('meeting_date')->nullable();
            $table->time('meeting_time', $precision = 0)->nullable();
            $table->json('meeting_point')->nullable();
            $table->json('start_point')->nullable();
            $table->json('end_point')->nullable();
            $table->integer('members_count');
            $table->string('gender', 3); // F , M , MIX
            $table->string('city');
            $table->string('status')->default('pending');
            // $table->string('')->default('private');
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
        Schema::dropIfExists('activities');
    }
}
