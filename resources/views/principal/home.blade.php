@extends('layouts.app')

@section('content')

<div class="container text-center text-white py-5">

    <h1 class="display-4 fw-bold">
        Control Escolarrrrrr
    </h1>

    <h3 class="mt-4">
        Bienvenido,
        {{ session('nombre') }}
    </h3>

    <p class="fs-5">
        {{ session('rol') }}
    </p>



</div>

@endsection