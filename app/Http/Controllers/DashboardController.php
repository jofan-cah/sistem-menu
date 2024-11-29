<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();

        return view('index'); // View khusus untuk Master
        // if ($user->role === 'Master') {
        // } elseif ($user->role === 'Karyawan') {
        //     return view('karyawan.dashboard'); // View khusus untuk Karyawan
        // }

        return abort(403, 'Unauthorized action.'); // Jika role tidak valid
    }



    public function cari(Request $request)
    {
        $query = Recipe::query()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('ingredient')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('id', $request->ingredient);
            });
        }

        $recipes = $query->get();
        $categories = Category::all();
        $ingredients = Ingredient::all();

        return view('cari', compact('recipes', 'categories', 'ingredients'));
    }
}
