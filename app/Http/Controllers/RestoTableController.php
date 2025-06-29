<?php

namespace App\Http\Controllers;

use QrCode;
use App\Models\Category;
use App\Models\RestoTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestoTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $tables = RestoTable::all();
        return view('tables.create', compact('tables'));
    }

    public function toggleLock(Request $request, $id)
{
    $table = RestoTable::findOrFail($id);
    $table->is_locked = $request->input('is_locked') ? 1 : 0;
    $table->save();

    return response()->json(['success' => true]);
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
        $validated = $request->validate([
            'number' => 'required|integer|unique:resto_tables,number',
            'name' => 'nullable|string|max:255',
        ]);
    
        // Create the table first
        $table = RestoTable::create($validated);
    
        // Generate the QR content (can be route or custom text)
        $qrContent = route('table.view', $table->id); 
    
        // Generate SVG QR code and store it
        $qrImage = \QrCode::format('svg')->size(300)->generate($qrContent);
        $filePath = "qr_codes/table_{$table->id}.svg";
        \Storage::disk('public')->put($filePath, $qrImage);
    
        // Save QR code path to table record
        $table->update(['qr_code_path' => $filePath]);
    
        return redirect()->route('tables.index')->with('success', 'Table created successfully!');
    }
    

    public function destroy($id)
    {
        $table = RestoTable::findOrFail($id);

        // Delete QR code image
        if ($table->qr_code_path) {
            Storage::disk('public')->delete($table->qr_code_path);
        }

        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $table = RestoTable::findOrFail($id);

    // Check if the table is NOT available (is_available = 0)
    if ($table->is_locked == 1) {
        // Redirect or show a message
        return view('tables.unavailable', compact('table'));
    }

    // Load only available menu items for each category
    $categories = Category::with(['menuItems' => function ($query) {
        $query->where('available', 1);
    }])->get();

    return view('tables.new-table', compact('table', 'categories'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RestoTable $restoTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestoTable $restoTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
 
}
