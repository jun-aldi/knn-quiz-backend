<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateQuestion;
use App\Http\Requests\UpdateQuestion;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 30);


        $questionQuery = Question::withCount('quiz');

        // Get single data
        if ($id) {
            $question = $questionQuery->with('quiz')->with('choices')->find($id);

            if ($question) {
                return ResponseFormatter::success($question, 'Question found');
            }

            return ResponseFormatter::error('Question not found', 404);
        }

        // Get multiple data
        $questions = $questionQuery->where('quiz_id', $request->quiz_id);


        return ResponseFormatter::success(
            $questions->paginate($limit),
            'Questions found'
        );
    }

    public function create(CreateQuestion $request)
    {

        try {

            //create Question
            $Question = Question::create([
                'description' => $request->description,
                'quiz_id' => $request->quiz_id,
            ]);

            //if not created
            if (!$Question) {
                throw new Exception('Question not created');
            }

            return ResponseFormatter::success($Question, 'Question Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateQuestion $request, $id)
    {
        try {
            // Get Question
            $Question = Question::find($id);

            // Check if Question exists
            if (!$Question) {
                throw new Exception('Question not found');
            }

            // Update Question
            $Question->update([
                'description' => $request->description,
                'quiz_id' => $request->quiz_id,
            ]);

            return ResponseFormatter::success($Question, 'Question updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Question
            $Question = Question::find($id);

            // Check if Question exists
            if (!$Question) {
                throw new Exception('Question not found');
            }

            // Delete Question
            $Question->delete();

            return ResponseFormatter::success('Question deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
