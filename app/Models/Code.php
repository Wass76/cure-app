<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Code extends Model
{
    use HasFactory;
    protected $fillable = ['activation_code'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'code_subject')->withTimestamps();
    }
}
