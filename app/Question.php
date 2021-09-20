<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function exam()
    {
        return $this->hasOne(Exam::class, 'id', 'exam_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(QuestionImage::class, 'question_id', 'id');
    }
}
