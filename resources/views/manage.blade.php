@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('/css/faktur.css') }}">
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
@endsection

@section('container')
<div class="container">
    <div class="row mt-4 mb-5">
        <div class="col">
            <h1><i class="bi bi-briefcase-fill"></i> Manage Functionalities</h1>
        </div>
    </div>
    <div class="row ">
        <div class="col py-4 d-flex justify-content-center">
            <a href="/create-items" class="card" style="width: 16rem; text-decoration:none; overflow:hidden;">
                <div class="d-flex flex-column card-add justify-content-center align-items-center " style="height:100%; width:inherit;">
                    <h1 class="nunito" style="font-size: 10em; font-weight:500;">+</h1>
                    <p class="nunito" style="font-size: 2em; font-weight:700;">Add Item</p>
                </div>
            </a>
        </div>
        <div class="col py-4 d-flex justify-content-center">
            <a href="/create-category" class="card" style="width: 16rem; text-decoration:none; overflow:hidden;">
                <div class="d-flex flex-column card-add justify-content-center align-items-center " style="height:100%; width:inherit;">
                    <h1 class="nunito" style="font-size: 10em; font-weight:500;">+</h1>
                    <p class="nunito" style="font-size: 2em; font-weight:700;">Add Category</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
