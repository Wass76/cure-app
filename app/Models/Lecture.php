<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relation\HasMany;
class Lecture extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subject_id'];

    public function audioLecture()
    {
        return $this->hasOne(AudioLecture::class);
    }

    public function pdfLecture()
    {
        return $this->hasOne(PdfLecture::class);
    }

}
