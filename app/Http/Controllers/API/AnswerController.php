<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnswer;
use App\Http\Requests\UpdateAnswer;
use App\Models\Answer;
use Exception;
use Illuminate\Http\Request;

class AnswerController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);


        $AnswerQuery = Answer::withCount('student');

        // Get single data
        if ($id) {
            $Answer = $AnswerQuery  ->find($id);

            if ($Answer) {
                return ResponseFormatter::success($Answer, 'Answer found');
            }

            return ResponseFormatter::error('Answer not found', 404);
        }

        // Get multiple data
        $Answers = $AnswerQuery->with('choice')->with('question')->where('student_id', $request->student_id);


        return ResponseFormatter::success(
            $Answers->paginate($limit),
            'Answers found'
        );
    }

    public function create(CreateAnswer $request)
    {

        try {

            //create Answer
            $Answer = Answer::create([
                // 'answer' => $request->answer,
                'student_id' => $request->student_id,
                'question_id' => $request->question_id,
                'choice_id' => $request->choice_id,
            ]);

            //if not created
            if (!$Answer) {
                throw new Exception('Answer not created');
            }

            return ResponseFormatter::success($Answer, 'Answer Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateAnswer $request, $id)
    {
        try {
            // Get Answer
            $Answer = Answer::find($id);

            // Check if Answer exists
            if (!$Answer) {
                throw new Exception('Answer not found');
            }

            // Update Answer
            $Answer->update([
                'choice_id' => $request->choice_id,
                'student_id' => $request->student_id,
                'question_id' => $request->question_id,
            ]);

            return ResponseFormatter::success($Answer, 'Answer updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Answer
            $Answer = Answer::find($id);

            // Check if Answer exists
            if (!$Answer) {
                throw new Exception('Answer not found');
            }

            // Delete Answer
            $Answer->delete();

            return ResponseFormatter::success('Answer deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
