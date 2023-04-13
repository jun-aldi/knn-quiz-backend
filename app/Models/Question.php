<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'description',
        'choice_1',
        'choice_2',
        'choice_3',
    ];

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
    //inverse one to many
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
