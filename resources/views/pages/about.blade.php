@extends('layouts.app')

@section('content')


<div class="page">

  <!-- HERO -->
  <div class="section hero">
    <h1 class="hero-title">Sobre Nosotros</h1>
    <p class="hero-sub">
      En 2026, un grupo apasionado de desarrolladores se unió para crear la mejor plataforma.
    </p>
  </div>

  <!-- DAVID -->
  <div class="section">
    <div class="member">
      <div class="member-img">
        <div class="imagen-grid-animada absolute inset-0" data-imagen="{{ asset('img/david.jpg') }}"></div>
      </div>
      <h2>DAVID ROLLON</h2>
      <p class="role" style="color:#1D9E75;">Backend Dev</p>
      <p class="desc">Arquitectura sólida y APIs robustas.</p>
    </div>
  </div>

  <!-- MAIN-->
  <div class="section">
    <div class="member">
      <div class="member-img">
        <div class="imagen-grid-animada absolute inset-0" data-imagen="{{ asset('img/main.png') }}"></div>
      </div>
      <h2>MAIN ALAM</h2>
      <p class="role" style="color:#EF9F27;">Frontend Dev</p>
      <p class="desc">Interfaces rápidas y accesibles.</p>
    </div>
  </div>

  <!-- GEO -->
  <div class="section">
    <div class="member">
      <div class="member-img">
        <div class="imagen-grid-animada absolute inset-0" data-imagen="{{ asset('img/animacion.png') }}"></div>
      </div>
      <h2>GEOVANNY JIMENEZ</h2>
      <p class="role" style="color:#7F77DD;">UI/UX Design</p>
      <p class="desc">Experiencias intuitivas y limpias.</p>
    </div>
  </div>

  <!-- HISTORIA -->
  <div class="section history">
    <h2>Nuestra Historia</h2>
    <p>Comenzamos como un proyecto de fin de grado.</p>
    <p>Hoy es una plataforma sólida.</p>
  </div>

</div>

@endsection