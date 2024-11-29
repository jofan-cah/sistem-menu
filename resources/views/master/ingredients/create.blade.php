@extends('template.dashboard')
@section('page-title', ' Add Ingredient')


@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add New Ingredient</h3>
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

    <form action="{{ route('ingredients.store') }}" method="POST">
      @csrf
      <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
      </div>
      <div class="form-group mb-3">
        <label for="unit">Unit</label>
        <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit') }}" required>
      </div>
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection