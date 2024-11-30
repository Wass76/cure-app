<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Code extends Model
{
    use HasFactory;
    protected $fillable = [
        'activation_code',
        'is_taken',
        'user_id'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'code_subject')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

}
