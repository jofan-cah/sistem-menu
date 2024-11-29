@extends('template.dashboard')
@section('page-title', 'Recipes Edit')

@section('content')
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-warning text-white">
      <h3 class="mb-0">Edit Recipe</h3>
    </div>
    <div class="card-body">
      @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('recipes.update', $recipe->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="{{ $recipe->name }}" required>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" class="form-control"
            required>{{ $recipe->description }}</textarea>
        </div>
        <div class="form-group">
          <label for="category_id">Category</label>
          <select name="category_id" id="category_id" class="form-control" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $recipe->category_id == $category->id ? 'selected' : '' }}>{{
              $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="ingredients">Ingredients</label>
          <div id="ingredients">
            @foreach($ingredients as $ingredient)
            <div class="form-check">
              <input type="checkbox" name="ingredients[{{ $loop->index }}][id]" value="{{ $ingredient->id }}"
                class="form-check-input" id="ingredient-{{ $ingredient->id }}" {{ $recipe->ingredients->contains('id',
              $ingredient->id) ? 'checked' : '' }}>
              <label class="form-check-label" for="ingredient-{{ $ingredient->id }}">{{ $ingredient->name }}</label>
              <input type="number" name="ingredients[{{ $loop->index }}][quantity]" class="form-control mt-1"
                placeholder="Quantity" min="1"
                value="{{ $recipe->ingredients->firstWhere('id', $ingredient->id)->pivot->quantity ?? '' }}">
            </div>
            @endforeach
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection