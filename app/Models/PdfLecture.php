<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfLecture extends Model
{
    use HasFactory;
    protected $fillable = ['file_name', 'file_size', 'lecture_id'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
