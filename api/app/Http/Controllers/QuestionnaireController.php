<?php

namespace App\Http\Controllers;

use App\QuestionnaireAnswers;
use App\QuestionnaireQuests;
use App\QuestionnaireQuestsAnswers;
use App\UserAnswers;
use Illuminate\Http\Request;

use App\Questionnaire;

use Illuminate\Support\Facades\Hash;
use Mail;

class QuestionnaireController extends ResponseController
{
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'desc')->get();
        $completedQuestionaires = UserAnswers::where('user_id', auth()->user()->id)->get();

        $result = Array(
            'questionaires'  => $questionaires,
            'userAnswers' => [],
            'keys' => [],
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

        $AnswersHash = "";
        foreach ($data['answers'] as $answer) {
            $AnswersHash .= $answer['answer_id'] . '#';
        }
        $AnswersHash .= auth()->user()->GetForHash();
        $AnswersHash = bcrypt($AnswersHash);

        $MailData = array('hash' => base64_encode($AnswersHash), 'name' => auth()->user()->first_name, 'last_name' => auth()->user()->last_name);
        Mail::send(['text'=>'mail'], $MailData, function($message) {
            $message->to(auth()->user()->email, auth()->user()->first_name .' '. auth()->user()->last_name)->subject
            ('Token do odpowiedzi');
            $message->from('gorecki.mke@gmail.com', 'Administrator (Mikołaj Górecki)');
        });

        foreach ($data['answers'] as $answer) {
            QuestionnaireAnswers::create([
                "questionnaire_id" => $data['id'],
                "quest_id" => $answer['quest_id'],
                "quest_answers_id" => $answer['answer_id'],
                "hash" => $AnswersHash
            ]);
        }

        return $this->success('answers saved');
    }

    public function check($questionnaireID, $key){
        $key = base64_decode($key);

        $questionnaire = Questionnaire::find($questionnaireID);
        if (!$questionnaire)
            return $this->notFound('unknown questionnaire id');

        $resultArray = [
            'main-information' => $questionnaire,
            'questions' => [],
            'status' => 2,
        ];

        $quests = QuestionnaireQuests::where('questionnaire_id', $questionnaireID)->get();
        if (!$quests->count())
            return $this->badRequest('no quests');

        $AnswersHash = '';

        $UsersAnswers = QuestionnaireAnswers::where([
            'hash' => $key,
        ])->get();

        if (!$UsersAnswers->count()) {
            $resultArray['status'] = 1;
            return $this->success($resultArray);
        }

        for ($index = 0; $index < $quests->count(); ++$index){
            array_push($resultArray['questions'], [
                'id' => $quests[$index]->id,
                'question' => $quests[$index]->question,
                'answers' => [],
                'user_answer' => 0,
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

            foreach ($UsersAnswers as $UserAnswer){
                if ($UserAnswer->quest_id == $resultArray['questions'][$index]['id']){
                    $resultArray['questions'][$index]['user_answer'] = $UserAnswer->quest_answers_id;
                    $AnswersHash .= $resultArray['questions'][$index]['user_answer'] . '#';
                }
            }
        }
        $AnswersHash .= auth()->user()->GetForHash();

        if (Hash::check($AnswersHash, $key))
            $resultArray['status'] = 0;

        return $this->success($resultArray);
    }
}
