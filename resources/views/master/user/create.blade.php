@extends('template.dashboard')
@section('page-title', 'User Create')


@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Create New User</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('users.store') }}" method="POST">
      @csrf
      <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter user name" required>
      </div>
      <div class="form-group mb-3">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter user email" required>
      </div>
      <div class="form-group mb-3">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>
      <div class="form-group mb-3">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
          placeholder="Re-enter password" required>
      </div>
      <div class="form-group mb-3">
        <label for="role">Role</label>
        <select id="role" name="role" class="form-control" required>
          <option value="Master">Master</option>
          <option value="Karyawan">Karyawan</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Create User</button>
    </form>
  </div>
</div>
@endsection