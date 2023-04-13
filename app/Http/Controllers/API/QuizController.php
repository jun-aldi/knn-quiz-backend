<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateQuiz;
use App\Http\Requests\UpdateQuiz;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $quizQuery = Quiz::query();

        // Get single data
        if ($id) {
            $quiz = $quizQuery->find($id);

            if ($quiz) {
                return ResponseFormatter::success($quiz, 'Quiz found');
            }

            return ResponseFormatter::error('quiz not found', 404);
        }

        //GET MULTIPLE DATA
        $quiz = $quizQuery;

        //berdasarkan nama
        if ($name) {
            $quizQuery->where('name', 'like', '%' . $name . '%');
        }

        //return response formatter JSON
        return ResponseFormatter::success(
            $quiz->paginate($limit),
            'quizs found'
        );
    }

    public function create(CreateQuiz $request)
    {

        try {

            //create student
            $quiz = Quiz::create([
                'name' => $request->name,
            ]);

            //if not created
            if (!$quiz) {
                throw new Exception('Quiz not created');
            }

            return ResponseFormatter::success($quiz, 'quiz Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateQuiz $request, $id)
    {
        try {
            // Get quiz
            $quiz = Quiz::find($id);

            // Check if quiz exists
            if (!$quiz) {
                throw new Exception('Quiz not found');
            }

            // Update quiz
            $quiz->update([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success($quiz, 'Quiz updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get quiz
            $quiz = Quiz::find($id);

            // Check if quiz exists
            if (!$quiz) {
                throw new Exception('Quiz not found');
            }

            // Delete quiz
            $quiz->delete();

            return ResponseFormatter::success('Quiz deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
