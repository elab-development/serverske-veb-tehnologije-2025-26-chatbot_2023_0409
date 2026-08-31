<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Question;

class Sport extends Model
{
    protected $fillable = [
        'name'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
