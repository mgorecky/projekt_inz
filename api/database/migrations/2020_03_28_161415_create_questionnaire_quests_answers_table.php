<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_quests_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->integer('quest_id')->unsigned();
            $table->timestamps();

            $table->foreign('quest_id')
                ->references('id')
                ->on('questionnaire_quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_quests_answers');
    }
}
