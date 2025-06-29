<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Validation failed: redirect with errors
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        // Other unexpected error
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

  

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::findOrFail($id);
            $category->update(['name' => $request->name]);

            return redirect()->back()->with('success', 'Category updated successfully!');
        }

        public function destroy($id)
        {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->back()->with('success', 'Category deleted successfully!');
        }

}
