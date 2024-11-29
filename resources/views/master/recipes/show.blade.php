@extends('template.dashboard')
@section('page-title', 'Recipe Details')

@section('content')
<div class="container my-5">
  <div class="card ">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">{{ $recipe->name }}</h3>
    </div>
    <div class="card-body">
      <p><strong>Category:</strong> {{ $recipe->category->name ?? 'No Category' }}</p>
      <p><strong>Description:</strong> {{ $recipe->description ?? 'No Description' }}</p>
      <p><strong>Created By:</strong> {{ $recipe->user->name }}</p>
      <p><strong>Updated By:</strong> {{ $recipe->updated_by }}</p>
    </div>
  </div>

  <div class="mt-4">
    <h4>Ingredients</h4>
    @if($recipe->ingredients->isEmpty())
    <div class="alert alert-warning">
      No ingredients added.
    </div>
    @else
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Ingredient Name</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recipe->ingredients as $index => $ingredient)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $ingredient->name }}</td>
            <td>{{ $ingredient->pivot->quantity }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <div class="mt-4">
    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Back to Recipes
    </a>
  </div>
</div>
@endsection