<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Models\Code;
use App\Exceptions\ModelNotFoundException;

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
}
