<?php
namespace App\Repositories;

use App\Models\AudioLecture;

class AudioLectureRepository
{
    public function getAll()
    {
        return AudioLecture::all();
    }

    public function findById($id)
    {
        return AudioLecture::findOrFail($id);
    }

    public function create(array $data)
    {
        return AudioLecture::create($data);
    }

    public function update(AudioLecture $audioLecture, array $data)
    {
        return $audioLecture->update($data);
    }

    public function delete(AudioLecture $audioLecture)
    {
        return $audioLecture->delete();
    }
}
