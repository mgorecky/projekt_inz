<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_quests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->integer('questionnaire_id')->unsigned();
            $table->timestamps();

            $table->foreign('questionnaire_id')
                  ->references('id')
                  ->on('questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_quests');
    }
}
