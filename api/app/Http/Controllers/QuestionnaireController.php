<?php

namespace App\Http\Controllers;

use App\QuestionnaireAnswers;
use App\QuestionnaireQuests;
use App\QuestionnaireQuestsAnswers;
use App\UserAnswers;
use Illuminate\Http\Request;

use App\Questionnaire;

class QuestionnaireController extends ResponseController
{
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'desc')->get();
        $completedQuestionaires = UserAnswers::where('user_id', auth()->user()->id)->get();

        $result = Array(
            'questionaires'  => $questionaires,
            'userAnswers' => [],
        );

        foreach($completedQuestionaires as $answer) {
            array_push($result['userAnswers'], [
                'questionaire_id' => $answer->questionnaire_id,
                'created_at' => $answer->created_at->toDateTimeString(),
            ]);
        }

        return $this->success($result);
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
                'id' => $quests[$index]->id,
                'question' => $quests[$index]->question,
                'answers' => [],
            ]);

            $answers = QuestionnaireQuestsAnswers::where('quest_id', $quests[$index]->id)->get();
            if (!$answers->count())
                return $this->badRequest('no answers');

            foreach ($answers as $answer) {
                array_push($resultArray['questions'][$index]['answers'], [
                    "answer_id" => $answer->id,
                    "value" => $answer->value,
                ]);
            }
        }

        return $this->success($resultArray);
    }

    public function store(Request $request){
        $data = json_decode($request->getContent(), true);

        $questionnaire = Questionnaire::find($data['id']);
        if (!$questionnaire)
            return $this->badRequest('unknown questionnaire id');

        foreach ($data['answers'] as $answer) {
            $CheckAnswer = QuestionnaireQuests::where('id', $answer['quest_id'])->get();
            if (!$CheckAnswer)
                return $this->badRequest('unknown question id');

            if ($CheckAnswer[0]['questionnaire_id'] != $data['id'])
                return $this->badRequest('question from other questionnaire');

            $CheckAnswer = QuestionnaireQuestsAnswers::where('id', $answer['answer_id'])->get();
            if (!$CheckAnswer)
                return $this->notFound('unknown answer id');

            if ($CheckAnswer[0]['quest_id'] != $answer['quest_id'])
                return $this->notFound('answer from other quest');
        }

        UserAnswers::create([
            "user_id" => auth()->user()->id,
            "questionnaire_id" => $data['id']
        ]);

        foreach ($data['answers'] as $answer) {
            QuestionnaireAnswers::create([
                "questionnaire_id" => $data['id'],
                "quest_id" => $answer['quest_id'],
                "quest_answers_id" => $answer['answer_id'],
                "hash" => bcrypt(auth()->user()->GetForHash())
            ]);
        }

        return $this->success('answers saved');
    }
}
