<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Models\Code;
use App\Exceptions\ModelNotFoundException;
use DB;

class SubjectRepository
{
    public function getAll()
    {
        return Subject::all();
    }

    public function findById($id)
    {
        return Subject::find($id);
    }
    // public function getAllSubjectsByCode(int $codeId)
    // {
    //     $code = Code::find($codeId);

    //     if (!$code) {
    //         throw new ModelNotFoundException("There is no such code" , 404);
    //     }
    //     return $code->subjects;
    // }

    public function getSubjectsByUserCodes($userId)
    {
        return Subject::whereHas('codes', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->distinct()
            ->get(['id', 'name', 'type', 'created_at', 'updated_at'])
            ->makeHidden('pivot');
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data)
    {
        return $subject->update($data);
    }

    public function delete(Subject $subject)
    {
        return $subject->delete();
    }

    public function countUsersBySubject()
    {
        return Subject::join('code_subject', 'subjects.id', '=', 'code_subject.subject_id')
            ->join('codes', 'code_subject.code_id', '=', 'codes.id')
            ->join('users', 'codes.user_id', '=', 'users.id')
            ->select('subjects.id as subject_id', 'subjects.name as subject_name', DB::raw('count(distinct users.id) as user_count'))
            ->groupBy('subjects.id', 'subjects.name')
            ->get();
    }
}
