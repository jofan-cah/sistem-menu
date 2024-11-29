@extends('template.dashboard')
@section('page-title', 'Recipes List')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">List of Recipes</h3>
    <div class="card-tools">
      <a href="{{ route('recipes.create') }}" class="btn btn-primary btn-sm">Add Recipe</a>
    </div>
  </div>
  <div class="card-body">
    @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
    @endif
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Category</th>
          <th>Created By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($recipes as $recipe)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $recipe->name }}</td>
          <td>{{ $recipe->category->name ?? 'N/A' }}</td>
          <td>{{ $recipe->user->name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="display: inline-block;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection