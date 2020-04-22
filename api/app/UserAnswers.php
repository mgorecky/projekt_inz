<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswers extends Model
{
    protected $hidden = [
        'remember_token', 'updated_at'
    ];
}
