@extends('template.dashboard')
@section('page-title', 'Ingredients List')


@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">List of Ingredients</h3>
    <div class="card-tools">
      <a href="{{ route('ingredients.create') }}" class="btn btn-primary btn-sm">Add Ingredient</a>
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
          <th>Unit</th>
          <th>Created By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($ingredients as $ingredient)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $ingredient->name }}</td>
          <td>{{ $ingredient->unit }}</td>
          <td>{{ $ingredient->creator->name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST"
              style="display: inline-block;">
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