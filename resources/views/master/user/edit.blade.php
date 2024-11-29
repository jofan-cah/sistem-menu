@extends('template.dashboard')
@section('page-title', 'User Edit')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit User</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password <small>(leave blank to keep current password)</small></label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="Master" {{ $user->role == 'Master' ? 'selected' : '' }}>Master</option>
                    <option value="Karyawan" {{ $user->role == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update User</button>
        </form>
    </div>
</div>
@endsection
