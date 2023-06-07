<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'question_id',
        'category_id',
    ];

    public function answer()
    {
        return $this->hasMany(Answer::class);
    }

    public function question()
    {
        return $this->hasOne(Question::class);
    }

    //inverse one to many
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
