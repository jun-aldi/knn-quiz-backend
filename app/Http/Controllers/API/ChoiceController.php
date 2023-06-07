<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateChoice;
use App\Http\Requests\UpdateChoice;
use App\Models\Choice;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);


        $choiceQuery = Choice::withCount('question');

        // Get single data
        if ($id) {
            $choice = $choiceQuery->with('question')->find($id);

            if ($choice) {
                return ResponseFormatter::success($choice, 'Choice found');
            }

            return ResponseFormatter::error('Choice not found', 404);
        }

        // Get multiple data
        $choices = $choiceQuery->where('question_id', $request->question_id);


        return ResponseFormatter::success(
            $choices->paginate($limit),
            'Choices found'
        );
    }

    public function create(CreateChoice $request)
    {

        try {

            //create Question
            $Choice = Choice::create([
                'name' => $request->name,
                'question_id' => $request->question_id,
                'category_id' => $request->category_id,
            ]);

            //if not created
            if (!$Choice) {
                throw new Exception('Choice not created');
            }

            return ResponseFormatter::success($Choice, 'Choice Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateChoice $request, $id)
    {
        try {
            // Get Question
            $Choice = Choice::find($id);

            // Check if Question exists
            if (!$Choice) {
                throw new Exception('Choice not found');
            }

            // Update Question
            $Choice->update([
                'name' => $request->name,
                'question_id' => $request->question_id,
                'category_id' => $request->category_id,
            ]);

            return ResponseFormatter::success($Choice, 'Choice updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Question
            $Choice = Choice::find($id);

            // Check if Question exists
            if (!$Choice) {
                throw new Exception('Choice not found');
            }

            // Delete Question
            $Choice->delete();

            return ResponseFormatter::success('Choice deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
