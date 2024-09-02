@extends('layout.index')


@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
@endsection

@section('container')
@can('is_admin')
    @if (session('alert'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em; position: absolute; width:100%;">
        <i class="bi bi-bag-check-fill"></i> {{ session('alert') }}
    </div>
    @endif
@endcan
@if (session('nullcart'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em; position: absolute; width:100%;">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('nullcart') }}
    </div>
@endif


<div class="container mt-5 ">
    <div class="row mb-2">
        <div class="col">
            <h1><i class="bi bi-box-seam-fill"></i> Items</h1>
        </div>
    </div>
    <div class="row">
        @can('is_admin')
        <div class="col-lg-4 col-md-6 col-sm-12 py-4 d-flex justify-content-center">
            <a href="/create-items" class="card" style="width: 14rem; text-decoration:none; overflow:hidden;">
                <div class="d-flex flex-column card-add justify-content-center align-items-center " style="height:100%; width:inherit;">
                    <h1 class="nunito" style="font-size: 10em; font-weight:500;">+</h1>
                    <p class="nunito" style="font-size: 2em; font-weight:700;">Add Item</p>
                </div>
            </a>
        </div>
        @endcan
        @forelse ( $barangs as $barang )
        <div class="col-lg-4 col-md-6 col-sm-12 py-4 d-flex justify-content-center">
            <div class="card" style="width: 14rem;">
                <img src="{{ Storage::disk('public')->exists('items-image/'.$barang->foto) ? asset('storage/items-image/'.$barang->foto) : asset('img/DEFAULT-PLACEHOLDER-2213.png') }}" class="card-img-top" alt="An image of {{ $barang->nama }}">
                <div class="card-body">
                    <h5 class="card-title nunito resetmp namabrg">{{ (strlen($barang->nama)>15) ? substr($barang->nama, 0, 10) . " ... " . substr($barang->nama, -5) : $barang->nama }}</h5>
                    <a href="/category/{{ $barang->kategori_id }}" class="kateg ">
                        <small class="poppins">{{ $barang->kategori->name }}</small>
                    </a>
                    <h6 class="card-text poppins harga resetmp mt-3 ">Rp. {{ number_format($barang->harga, 0, ',', '.') }},00</h6>
                    <h6 class="card-text poppins jmlh resetmp mt-1">Stock: {{ $barang->jumlah }}</h6>
                    <a href="/items/{{ $barang->id }}" class="d-flex justify-content-center mt-4" style="text-decoration: none">
                        <button class="btn-go poppins bblac">View Item</button>
                    </a>
                </div>
            </div>
        </div>

        @empty

        <h1 class="nunito d-flex align-items-center justify-content-center" id="nofound">Couldn't Find Anything<i class="bi bi-emoji-frown-fill ms-5"></i></h1>
        @endforelse
    </div>
    @if (!$habiss->isEmpty())
        <hr>
        <div class="row mb-2 mt-5">
            <div class="col">
                <h1><i class="bi bi-ban"></i> Out Of Stock</h1>
            </div>
        </div>
        <div class="row">
            @foreach ( $habiss as $habis )
                <div class="col-lg-4 col-md-6 col-sm-12 py-4 d-flex justify-content-center">
                    <div class="card" style="width: 14rem;">
                        <img src="{{ Storage::disk('public')->exists('items-image/'.$habis->foto) ? asset('storage/items-image/'.$habis->foto) : asset('storage/items-image/DEFAULT-PLACEHOLDER-2213.png') }}" class="card-img-top" alt="An image of {{ $habis->nama }}">
                        <div class="card-body">
                            <h5 class="card-title nunito resetmp namabrg">{{ (strlen($habis->nama)>15) ? substr($habis->nama, 0, 10) . " ... " . substr($habis->nama, -5) : $habis->nama }}</h5>
                            <a href="/categories/{{ $habis->kategori_id }}" class="kateg ">
                                <small class="poppins">{{ $habis->kategori->name }}</small>
                            </a>
                            <h6 class="card-text poppins harga resetmp mt-3 ">Rp. {{ number_format($habis->harga, 0, ',', '.') }},00</h6>
                            <h6 class="card-text poppins jmlh resetmp mt-1">Stock: {{ $habis->jumlah }}</h6>
                            <a href="/items/{{ $habis->id }}" class="d-flex justify-content-center mt-4" style="text-decoration: none">
                                <button class="btn-go poppins bblac">View Item</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

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
