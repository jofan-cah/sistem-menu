<?php
// app/Http/Controllers/RecipeController.php
namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('category')->get();
        return view('master.recipes.index', compact('recipes'));
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['category', 'ingredients']);
        return view('master.recipes.show', compact('recipe'));
    }


    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        return view('master.recipes.create', compact('categories', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1',
        ]);

        $recipe = Recipe::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
        }

        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully.');
    }

    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        $recipe->load('ingredients');
        return view('master.recipes.edit', compact('recipe', 'categories', 'ingredients'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1',
        ]);

        $recipe->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'updated_by' => Auth::id(),
        ]);

        $recipe->ingredients()->detach();
        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
        }

        return redirect()->route('recipes.index')->with('success', 'Recipe updated successfully.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully.');
    }
}
