@extends('template.dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <h1 class="mb-4">Selamat Datang, {{ Auth::user()->name }}!</h1>
          <p class="text-muted mb-4">Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <div class="card border-primary text-center">
        <div class="card-body">
          <blockquote class="blockquote">
            <p class="mb-0">"Kerja keras hari ini adalah bekal untuk kesuksesan masa depan."</p>
            <footer class="blockquote-footer mt-2">Motivasi Harian</footer>
          </blockquote>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection