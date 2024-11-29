@extends('template.dashboard')
@section('page-title', 'Category List')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">List of Categories</h3>
    <div class="card-tools">
      <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($categories as $category)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $category->name }}</td>
          <td>{{ $category->description }}</td>
          <td>
            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
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
@stop