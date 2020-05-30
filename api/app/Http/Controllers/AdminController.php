<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\QuestionnaireAnswers;
use App\QuestionnaireQuests;
use App\QuestionnaireQuestsAnswers;
use App\UserAnswers;
use Illuminate\Http\Request;


/**
 * @group AdminController
 * APIs for front page actions with Questionnaires by Admin
 */
class AdminController extends ResponseController
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
     *            ]
     *        }
     * }
     */
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'asc')->get();

        $result = Array(
            'questionaires'  => $questionaires,
        );

        return $this->success($result);
    }

    /**
     * Provide Questionnaire results
     * Provide all inormation and vote results about Questionnaire with given ID
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
     * "id": 0,
     * "question": "Czy strona działa prawidłowo?",
     * "answers": [
     * {
     * "answer_id": 42,
     * "value": "Tak",
     * "count": 1
     * },
     * {
     * "answer_id": 43,
     * "value": "Nie",
     * "count": 1
     * }
     * ]
     * },
     * {
     * "id": 17,
     * "question": "Wybierz uczelnię na jakiej się uczysz",
     * "answers": [
     * {
     * "answer_id": 44,
     * "value": "Uniwersytet Pedagogiczny w Krakowie",
     * "count": 0
     * },
     * {
     * "answer_id": 45,
     * "value": "Uniwersytet Gdański",
     * "count": 0
     * },
     * {
     * "answer_id": 46,
     * "value": "Politechnika Wrocławska",
     * "count": 1
     * },
     * {
     * "answer_id": 47,
     * "value": "Żadne z powyższych",
     * "count": 1
     * }
     * ]
     * },
     * {
     * "id": 18,
     * "question": "Czy pochodzisz z Krakowa?",
     * "answers": [
     * {
     * "answer_id": 48,
     * "value": "Tak",
     * "count": 1
     * },
     * {
     * "answer_id": 49,
     * "value": "Nie",
     * "count": 1
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
     * @response 404 {
     * "status": "Not Found",
     * "status_code": 404,
     * "message": "questrionnaire without answers"
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

    /**
     * Save Questionnaire information
     * Save Questionnaire information after create by Admin
     * @bodyParam title string required Questionnaire's title. Example: Testowa ankieta
     * @bodyParam start_time string required Questionnaire's start time - mysql datetime format. Example: 2020-05-30 00:00:00
     * @bodyParam end_time string required Questionnaire's start time - mysql datetime format. Example: 2020-06-16 00:00:00
     * @bodyParam questions array required Questionnaire's details. Example: [{"question":"Pytanie nr 1","answers":["Odpowiedz 1", "Odpowiedz 2", "Odpowiedz3"]},{"question":"Pytanie nr 1","answers":["Odpowiedz 1", "Odpowiedz 2", "Odpowiedz3"]}]
     * @authenticated
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "message": "OK",
     * "data": "answers saved"
     * }
     *
     */
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
