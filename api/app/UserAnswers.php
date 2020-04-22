<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'questionnaire_id', 'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'updated_at'
    ];
}
