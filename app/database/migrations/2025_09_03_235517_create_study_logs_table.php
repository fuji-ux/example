<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->string('material_name', 255);
            $table->string('category', 50);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration')->default(0);
            $table->tinyInteger('focus_score');
            $table->tinyInteger('understanding_score');
            $table->tinyInteger('motivation_score');
            $table->text('comment')->nullable();
            $table->boolean('ai_analyzed')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_logs');
    }
}
