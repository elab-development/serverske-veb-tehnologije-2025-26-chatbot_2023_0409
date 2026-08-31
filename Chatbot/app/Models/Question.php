<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question',
        'category_id',
        'sport_id',
        'keywords'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
