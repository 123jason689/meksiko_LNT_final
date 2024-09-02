@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('container')
@can('is_admin')
    @if (session('alert'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em; position: absolute; width:100%;">
        <i class="bi bi-bag-check-fill"></i> {{ session('alert') }}
    </div>
    @endif
@endcan

<div class="container mt-4 ">
    <div class="row mb-2">
        <div class="col">
            <h1><i class="bi bi-box-seam-fill"></i> Categories</h1>
        </div>
    </div>
    <div class="row" style=" overflow:hidden; border-radius: 2em; height: 73vh;">
        {{-- <h1 style="text-align: center; height:5%">Create Category</h1> --}}
        <div class="col d-flex flex-column justify-content-center align-items-center bblac" style="height: 100%">
            <div class="card d-flex flex-column justify-content-center align-items-center" style="width: 30vw; font-size:4em;">
                <div id="" class="card-logo d-flex flex-column justify-content-center align-items-center resetmp " style="height: 2em; width:2em;">
                    <img src="{{ asset('img/main-logo.png') }}" alt="Logo mexico" id="" class="" style="width: 2em; height:2em;">
                </div>
                <form action="/create-category" method="POST" class="card-body d-flex flex-column justify-content-center align-items-center">
                    @csrf
                    <h5 class="card-title poppins" style="font-size: inherit">Category</h5>
                    <p class="card-text nunito mt-4 d-flex flex-column justify-content-center align-items-center" style="font-size: 0.5em;">Category Name: </p>
                    <input type="text" name="kategori" id="" placeholder="Input a category name" style="font-size: 0.5em;" class="mb-4">
                    @error('kategori')
                    <div class="resetmp d-flex justify-content-center ">
                        <div class="col-8 resetmp px-2 ">
                            <div class="alert alert-danger nunito alerts resetmp " style="font-size: 1.7vw; text-align:center;">{{ $message }}</div>
                        </div>
                    </div>
                    @enderror
                    <button type="submit" class="btn-go" style="padding: 0em 1em 0em 1em">Create</button>
                </div>
            </div>
        </form>
    </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function(){
        setTimeout(() => {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

</script>
@endsection
