<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'auth.api'
], function () {
    Route::resources( [
        'questionnaires' => 'QuestionnaireController',
    ]);

    Route::get('questionnaires/{questionnaireID}/check/{key}', 'QuestionnaireController@check');
    Route::get('logout', 'AuthController@logout');
});

Route::group([
    'middleware' => 'admin.api'
], function () {
    Route::resources( [
        'admin' => 'AdminController',
    ]);
});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
