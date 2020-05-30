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

/**
 * @group QuestionnaireController
 * APIs for front page actions with Questionnaires by Users
 */
class QuestionnaireController extends ResponseController
{
    /**
     * Fetch all Questionnaires
     * Fetch all Questionnaires and informations which of them has been completed by user
     * @authenticated
     *
     * @response {
     *        "status": "success",
     *        "code": 200,
     *        "message": "OK",
     *        "data": {
     *            "questionaires": [
     *                {
     *                    "id": 11,
     *                    "title": "Testowa ankieta nr 1",
     *                    "start_time": "2020-05-30 00:00:00",
     *                    "end_time": "2020-06-16 00:00:00"
     *                }
     *            ],
     *            "userAnswers": [
     *                {
     *                    "questionaire_id": 11,
     *                    "created_at": "2020-05-30 14:33:56"
     *                }
     *            ],
     *            "keys": []
     *        }
     * }
     */
    public function index()
    {
        $questionaires = Questionnaire::orderBy('end_time', 'desc')->get();
        $completedQuestionaires = UserAnswers::where('user_id', auth()->user()->id)->get();

        $result = Array(
            'questionaires' => $questionaires,
            'userAnswers' => [],
            'keys' => [],
        );

        foreach ($completedQuestionaires as $answer) {
            array_push($result['userAnswers'], [
                'questionaire_id' => $answer->questionnaire_id,
                'created_at' => $answer->created_at->toDateTimeString(),
            ]);
        }

        return $this->success($result);
    }

    /**
     * Provide Questionnaire information
     * Provide all inormation about Questionnaire with given ID
     * @urlParam questionnaireID required Questionnaire's ID which informations should be provided. Example: 11
     * @authenticated
     *
     * @response {
     * "status": "success",
     * "code": 200,
     * "message": "OK",
     * "data": {
     * "main-information": {
     * "id": 11,
     * "title": "Testowa ankieta nr 1",
     * "start_time": "2020-05-30 00:00:00",
     * "end_time": "2020-06-16 00:00:00"
     * },
     * "questions": [
     * {
     * "id": 16,
     * "question": "Czy strona działa prawidłowo?",
     * "answers": [
     * {
     * "answer_id": 42,
     * "value": "Tak"
     * },
     * {
     * "answer_id": 43,
     * "value": "Nie"
     * }
     * ]
     * },
     * {
     * "id": 17,
     * "question": "Wybierz uczelnię na jakiej się uczysz",
     * "answers": [
     * {
     * "answer_id": 44,
     * "value": "Uniwersytet Pedagogiczny w Krakowie"
     * },
     * {
     * "answer_id": 45,
     * "value": "Uniwersytet Gdański"
     * },
     * {
     * "answer_id": 46,
     * "value": "Politechnika Wrocławska"
     * },
     * {
     * "answer_id": 47,
     * "value": "Żadne z powyższych"
     * }
     * ]
     * },
     * {
     * "id": 18,
     * "question": "Czy pochodzisz z Krakowa?",
     * "answers": [
     * {
     * "answer_id": 48,
     * "value": "Tak"
     * },
     * {
     * "answer_id": 49,
     * "value": "Nie"
     * }
     * ]
     * }
     * ]
     * }
     * }
     *
     * @response 404 {
     * "status": "Not Found",
     * "status_code": 404,
     * "message": "unknown questionnaire id"
     * }
     *
     * @response 400 {
     * "status": "bad request",
     * "status_code": 400,
     * "message": "no quests"
     * }
     *
     * @response 400 {
     * "status": "bad request",
     * "status_code": 400,
     * "message": "no answers"
     * }
     */
    public function show($questionnaireID)
    {
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

        for ($index = 0; $index < $quests->count(); ++$index) {
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

    /**
     * Save user answers
     * Save user's answers, generate token and send token via email used in register
     * @bodyParam id int required Questionnaire's ID which user answers. Example: 11
     * @bodyParam answers array required User's answers. Example: [{"quest_id":0,"answer_id":2},{"quest_id":1,"answer_id":5},{"quest_id":2,"answer_id":8}]
     * @authenticated
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "message": "OK",
     * "data": "answers saved"
     * }
     *
     * @response 400 {
     * "status": "bad request",
     * "status_code": 400,
     * "message": "unknown questionnaire id"
     * }
     *
     * @response 400 {
     * "status": "bad request",
     * "status_code": 400,
     * "message": "question from other questionnaire"
     * }
     *
     * @response 404 {
     * "status": "Not Found",
     * "status_code": 404,
     * "message": "unknown answer id"
     * }
     *
     * @response 404 {
     * "status": "Not Found",
     * "status_code": 404,
     * "message": "answer from other quest"
     * }
     */
    public function store(Request $request)
    {
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
        Mail::send(['text' => 'mail'], $MailData, function ($message) {
            $message->to(auth()->user()->email, auth()->user()->first_name . ' ' . auth()->user()->last_name)->subject
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

    /**
     * Check user's answers
     * Check user's answers and provide information if our answers has been modified and not match with answers in token
     * @urlParam questionnaireID required Questionnaire's ID which informations should be provided. Example: 11
     * @urlParam key required Token generated when saving user's answers. Example: JDJ5JDEwJDYwR204Q29yUGdobkNWcVF6QVJBSGVEYjBNLmxaREpnVjN2dW9NWFlzUDdmdkRXcTd1Z05x
     * @authenticated
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "id":11,
     * "title":"Testowa ankieta nr 1",
     * "start_time":"2020-05-30 00:00:00",
     * "end_time":"2020-06-16 00:00:00",
     * "questions":[
     * {
     * "id":16,
     * "question":"Czy strona działa prawidłowo?",
     * "answers":[
     * {
     * "answer_id":42,
     * "value":"Tak"
     * },
     * {
     * "answer_id":43,
     * "value":"Nie"
     * }
     * ],
     * "user_answer":43
     * },
     * {
     * "id":17,
     * "question":"Wybierz uczelnię na jakiej się uczysz",
     * "answers":[
     * {
     * "answer_id":44,
     * "value":"Uniwersytet Pedagogiczny w Krakowie"
     * },
     * {
     * "answer_id":45,
     * "value":"Uniwersytet Gda\u0144ski"
     * },
     * {
     * "answer_id":46,
     * "value":"Politechnika Wrocławska"
     * },
     * {
     * "answer_id":47,
     * "value":"Żadne z powyższych"
     * }
     * ],
     * "user_answer":47
     * },
     * {
     * "id":18,
     * "question":"Czy pochodzisz z Krakowa?",
     * "answers":[
     * {
     * "answer_id":48,
     * "value":"Tak"
     * },
     * {
     * "answer_id":49,
     * "value":"Nie"
     * }
     * ],
     * "user_answer":49
     * }
     * ],
     * "status":0
     * }
     * }
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "id":11,
     * "title":"Testowa ankieta nr 1",
     * "start_time":"2020-05-30 00:00:00",
     * "end_time":"2020-06-16 00:00:00",
     * "questions":[],
     * "status":1
     * }
     * }
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "id":11,
     * "title":"Testowa ankieta nr 1",
     * "start_time":"2020-05-30 00:00:00",
     * "end_time":"2020-06-16 00:00:00",
     * "questions":[],
     * "status":2
     * }
     * }
     *
     * @response 404 {
     * "status": "Not Found",
     * "status_code": 404,
     * "message": "unknown questionnaire id"
     * }
     *
     * @response 400 {
     * "status": "bad request",
     * "status_code": 400,
     * "message": "no quests"
     * }
     */
    public function check($questionnaireID, $key)
    {
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

        for ($index = 0; $index < $quests->count(); ++$index) {
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

            foreach ($UsersAnswers as $UserAnswer) {
                if ($UserAnswer->quest_id == $resultArray['questions'][$index]['id']) {
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
