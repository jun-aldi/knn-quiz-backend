<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStudent;
use App\Http\Requests\UpdateStudent;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $studentQuery = Student::query();

        // Get single data
        if ($id) {
            $student = $studentQuery->find($id);

            if ($student) {
                return ResponseFormatter::success($student, 'student found');
            }

            return ResponseFormatter::error('student not found', 404);
        }

        $student = $studentQuery;
        //berdasarkan nama
        if ($name) {
            $studentQuery->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $student->paginate($limit),
            'Students found'
        );
    }

    public function create(CreateStudent $request)
    {

        try {

            //create student
            $student = Student::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
            ]);

            //if not created
            if (!$student) {
                throw new Exception('Student not created');
            }

            return ResponseFormatter::success($student, 'Student Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateStudent $request, $id)
    {
        try {
            // Get student
            $student = Student::find($id);

            // Check if student exists
            if (!$student) {
                throw new Exception('student not found');
            }

            // Update student
            $student->update([
                'name' => $request->name,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
            ]);

            return ResponseFormatter::success($student, 'student updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get student
            $student = Student::find($id);

            // Check if student exists
            if (!$student) {
                throw new Exception('student not found');
            }

            // Delete student
            $student->delete();

            return ResponseFormatter::success('student deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
