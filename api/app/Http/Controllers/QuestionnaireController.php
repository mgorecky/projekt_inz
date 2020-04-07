<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Questionnaire;

class QuestionnaireController extends ResponseController
{
    public function index(){
        $questionaires = Questionnaire::orderBy('end_time', 'asc')->get();
        return $this->success($questionaires);
    }
}
