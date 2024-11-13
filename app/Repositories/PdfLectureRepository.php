<?php
namespace App\Repositories;

use App\Models\PdfLecture;

class PdfLectureRepository
{
    public function getAll()
    {
        return PdfLecture::all();
    }

    public function findById($id)
    {
        return PdfLecture::find($id);
    }

    public function create(array $data)
    {
        $pdfLecture = PdfLecture::create($data);
        // echo $data['file_name'];
        return $pdfLecture;

    }

    public function update(PdfLecture $pdfLecture, array $data)
    {
        return $pdfLecture->update($data);
    }

    public function delete(PdfLecture $pdfLecture)
    {
        return $pdfLecture->delete();
    }
}
