<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    public function codes()
    {
        return $this->belongsToMany(Code::class, 'code_subject')->withTimestamps();
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

}
