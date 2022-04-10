<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'amount', 'duration', 'answer_id'];

    /**
     * Get all of the answers for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }


    public function answer()
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }

}
