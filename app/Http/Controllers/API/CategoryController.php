<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategory;
use App\Http\Requests\UpdateCategory;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 30);


        $categoryQuery = Category::withCount('choice');

        // Get single data
        if ($id) {
            $category = $categoryQuery->with('choice')->find($id);

            if ($category) {
                return ResponseFormatter::success($category, 'Category found');
            }

            return ResponseFormatter::error('Category not found', 404);
        }

        // Get multiple data
        $categories = $categoryQuery;


        return ResponseFormatter::success(
            $categories->paginate($limit),
            'Categories found'
        );
    }

    public function create(CreateCategory $request)
    {

        try {

            //create Question
            $Category = Category::create([
                'name' => $request->name,
            ]);

            //if not created
            if (!$Category) {
                throw new Exception('Category not created');
            }

            return ResponseFormatter::success($Category, 'Category Created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateCategory $request, $id)
    {
        try {
            // Get Question
            $Category = Category::find($id);

            // Check if Question exists
            if (!$Category) {
                throw new Exception('Category not found');
            }

            // Update Question
            $Category->update([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success($Category, 'Category updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Question
            $Category = Category::find($id);

            // Check if Question exists
            if (!$Category) {
                throw new Exception('Category not found');
            }

            // Delete Question
            $Category->delete();

            return ResponseFormatter::success('Category deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
