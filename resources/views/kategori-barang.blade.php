@extends('layout.index')


@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
@endsection

@section('container')
<div class="container mt-5 ">
    <div class="row mb-2">
        <div class="col">
            <h1><i class="bi bi-box-seam-fill"></i> Category-{{ $kategori->name }}</h1>
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
                <img src="{{ Storage::disk('public')->exists('items-image/'.$barang->foto) ? asset('storage/items-image/'.$barang->foto) : asset('storage/items-image/DEFAULT-PLACEHOLDER-2213.png') }}" class="card-img-top" alt="An image of {{ $barang->nama }}">
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
</div>
@endsection
