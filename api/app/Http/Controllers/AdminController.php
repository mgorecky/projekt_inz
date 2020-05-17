<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\QuestionnaireAnswers;
use App\QuestionnaireQuests;
use App\QuestionnaireQuestsAnswers;
use App\UserAnswers;
use Illuminate\Http\Request;

class AdminController extends ResponseController
{
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'asc')->get();

        $result = Array(
            'questionaires'  => $questionaires,
        );

        return $this->success($result);
    }

    public function show($questionnaireID){
        $questionnaire = Questionnaire::find($questionnaireID);
        if (!$questionnaire)
            return $this->notFound('unknown questionnaire id');

        $questionnaireAnswers = QuestionnaireAnswers::where('questionnaire_id', $questionnaireID)->get();
        if (!$questionnaireAnswers)
            return $this->notFound('questrionnaire without answers');

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
                'all' => 0,
            ]);

            $countAll = QuestionnaireAnswers::where('quest_id', $quests[$index]->id)->get();
            if ($countAll->count())
                $resultArray['questions'][$index]['all'] = $countAll->count();

            $answers = QuestionnaireQuestsAnswers::where('quest_id', $quests[$index]->id)->get();
            if (!$answers->count())
                return $this->badRequest('no answers');

            foreach ($answers as $answer) {
                $questionnaireAnswers = QuestionnaireAnswers::where('questionnaire_id', $questionnaireID)->where('quest_answers_id', $answer->id)->get();
                $AnsersCount = 0;
                if ($questionnaireAnswers)
                    $AnsersCount = $questionnaireAnswers->count();

                array_push($resultArray['questions'][$index]['answers'], [
                    "answer_id" => $answer->id,
                    "value" => $answer->value,
                    "count" => $AnsersCount,
                ]);
            }
        }

        return $this->success($resultArray);
    }

    public function store(Request $request){
        $data = json_decode($request->getContent(), true);

        $Questionnaire = Questionnaire::create([
            "title" => $data['title'],
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "creator_id" => auth()->user()->id
        ]);

        foreach($data["questions"] as $question){
            $NewQuestion = QuestionnaireQuests::create([
                "question" => $question['question'],
                "questionnaire_id" => $Questionnaire->id
            ]);

            foreach($question["answers"] as $answer){
                $NewAnswer = QuestionnaireQuestsAnswers::create([
                    "value" => $answer,
                    "quest_id" => $NewQuestion->id
                ]);
            }
        }

        return $this->success('questionnaire created');
    }
}
