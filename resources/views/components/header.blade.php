<div class="bg-white shadow-sm p-3 d-flex justify-content-between align-items-center">
  <h1 class="h5 mb-0">Dashboard</h1>
  <div>
    <span class="mr-3">{{ Auth::user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-danger btn-sm">Logout</button>
    </form>
  </div>
</div>