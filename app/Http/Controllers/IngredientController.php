<?php
// app/Http/Controllers/IngredientController.php
namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('master.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('master.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        Ingredient::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('ingredients.index')->with('success', 'Ingredient created successfully.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('master.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        $ingredient->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated successfully.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted successfully.');
    }
}
