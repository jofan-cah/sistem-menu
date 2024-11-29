@extends('template.dashboard')
@section('page-title', 'Cari Recipes')

@section('content')
<div class="container">
  <form action="{{ route('karyawan.cari') }}" method="GET" class="mb-3">
    <div class="row">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name"
          value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="category" class="form-control">
          <option value="">-- All Categories --</option>
          @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select name="ingredient" class="form-control">
          <option value="">-- All Ingredients --</option>
          @foreach($ingredients as $ingredient)
          <option value="{{ $ingredient->id }}" {{ request('ingredient')==$ingredient->id ? 'selected' : '' }}>
            {{ $ingredient->name }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Search</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Category</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($recipes as $recipe)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $recipe->name }}</td>
        <td>{{ $recipe->category->name ?? 'N/A' }}</td>
        <td>{{ $recipe->description }}</td>
        <td>
          <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-info btn-sm">View</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center">No recipes found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection