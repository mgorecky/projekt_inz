<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'title', 'start_time', 'end_time'
    ];

    protected $hidden = [
        'creator_id', 'created_at', 'updated_at'
    ];
}
