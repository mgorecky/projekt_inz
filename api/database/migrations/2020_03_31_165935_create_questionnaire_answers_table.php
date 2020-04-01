<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->integer('quest_answers_id')->unsigned();
            $table->timestamps();

            $table->foreign('questionnaire_id')
                ->references('id')
                ->on('questionnaires');
            $table->foreign('quest_id')
                ->references('id')
                ->on('questionnaire_quests');
            $table->foreign('quest_answers_id')
                ->references('id')
                ->on('questionnaire_quests_answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_answers');
    }
}
