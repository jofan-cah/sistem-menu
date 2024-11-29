@extends('template.dashboard')
@section('page-title', 'Category Show')

@section('content_header')
    <h1>Category Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>Name:</label>
                <p>{{ $category->name }}</p>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <p>{{ $category->description }}</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@stop
