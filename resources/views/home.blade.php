@extends('layout.index')

@section('container')

<div class="container-fluid px-5" style="height: 90vh; background-color: azure;">
    <div class="row align-items-center" style="height: 100%">
        <div class="col-6 justify-content-start align-items-center px-5">
            <h1 style="font-size: 5vw;" class="poppins">Empowering<br>Warehouses<br><a href="/items" class="round-btn">Right From Web</a></h1>
        </div>
        <div class="col-6 d-flex flex-column align-items-center">
            <div id="logocont" class="hero ">
                <img src="{{ asset('img/main-logo.png') }}" alt="Logo mexico" id="herologo" class="">
            </div>
            <h1 class="protest-g" style="font-size: 10vw; letter-spacing: -11px; line-height:8vw">Meksiko</h1>
        </div>
    </div>
</div>

@endsection
