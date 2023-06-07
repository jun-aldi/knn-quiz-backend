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
        $user_id = $request->input('user_id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $studentQuery = Student::with('user');

        // Get single data
        if ($user_id) {
            $student = $studentQuery->where('user_id', $user_id)->first();;

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
                // 'name' => $request->name,
                // 'nim' => $request->nim,
                // 'prodi' => $request->prodi,
                'amount_visual' => $request->amount_visual,
                'amount_kinesthetic' => $request->amount_kinesthetic,
                'amount_auditorial' => $request->amount_auditorial,
                'type' => $request->type,
                'user_id' => $request->user_id,
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
                // 'name' => $request->name,
                // 'nim' => $request->nim,
                // 'prodi' => $request->prodi,
                'amount_visual' => $request->amount_visual,
                'amount_kinesthetic' => $request->amount_kinesthetic,
                'amount_auditorial' => $request->amount_auditorial,
                'type' => $request->type,
                'user_id' => $request->user_id,
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
