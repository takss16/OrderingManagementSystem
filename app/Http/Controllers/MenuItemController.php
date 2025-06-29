<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('menuItems')->get(); 
        // dd($categories);
        return view('menu.index', compact('categories'));
    }
    public function showCategories()
        {
            $categories = Category::all();
            return view('menu.categories', compact('categories'));
        }

        public function showByCategory($id)
        {
            $category = Category::with('menuItems')->findOrFail($id);
            return view('menu.items', compact('category'));
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
     */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:menu_items,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle checkbox for availability
        $validated['available'] = $request->boolean('available');

        MenuItem::create($validated);

        return redirect()->back()->with('success', 'Menu item added successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
    }
}




    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            // 'image'       => 'nullable',
            'available'   => 'nullable|boolean',
        ]);
        
        if ($request->hasFile('image')) {
            // delete old image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('menu_items', 'public');
        }

        $validated['available'] = $request->boolean('available');

        $item->update($validated);

        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Menu item deleted successfully.');
    }
}
