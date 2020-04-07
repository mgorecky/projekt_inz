<?php

namespace App\Http\Controllers;

use App\QuestionnaireQuests;
use App\QuestionnaireQuestsAnswers;
use Illuminate\Http\Request;

use App\Questionnaire;

class QuestionnaireController extends ResponseController
{
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'asc')->get();
        return $this->success($questionaires);
    }

    public function show($questionnaireID){
        $questionnaire = Questionnaire::find($questionnaireID);
        if (!$questionnaire)
            return $this->notFound('unknown questionnaire id');

        $resultArray = [
            'main-information' => $questionnaire,
            'questions' => []
        ];

        $quests = QuestionnaireQuests::where('questionnaire_id', $questionnaireID)->get();
        if (!$quests->count())
            return $this->badRequest('no quests');

        for ($index = 0; $index < $quests->count(); ++$index){
            array_push($resultArray['questions'], [
                'id' => $index,
                'question' => $quests[$index]->question,
                'answers' => [],
            ]);

            $answers = QuestionnaireQuestsAnswers::where('quest_id', $quests[$index]->id)->get();
            if (!$answers->count())
                return $this->badRequest('no answers');

            foreach ($answers as $answer) {
                array_push($resultArray['questions'][$index]['answers'], $answer->value);
            }
        }

        return $this->success($resultArray);
    }
}
