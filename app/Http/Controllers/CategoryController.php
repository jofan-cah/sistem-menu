<?php
// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('master.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('master.categories.create');
    }

    public function show($id)
    {
        // Temukan kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Return view dengan data kategori
        return view('master.categories.show', compact('category'));
    }


    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('master.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
