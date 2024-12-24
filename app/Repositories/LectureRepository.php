<?php

namespace App\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Models\Lecture;
use App\Models\Code;

class LectureRepository
{
    public function getAll()
    {
        return Lecture::all();
    }

    public function findById($id)
    {
        // echo $id;
        return Lecture::find($id);
    }

    public function findBySubject($id)
    {
        return Lecture::where('subject_id' , $id)->get();
    }

    public function create(array $data)
    {
        return Lecture::create($data);
    }

    public function update(Lecture $lecture, array $data)
    {
        return $lecture->update($data);
    }

    public function delete(Lecture $lecture)
    {
        return $lecture->delete();
    }

    public function countLecturesBySubject(int $subjectId)
    {
        // Count lectures by subject_id
        return Lecture::where('subject_id', $subjectId)->count();
    }
}
