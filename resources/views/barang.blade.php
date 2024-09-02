@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.nice-number.css') }}">
@endsection

@section('container')
@if(session('alert'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em;">
        <i class="bi bi-bag-check-fill"></i> {{ session('alert') }}
    </div>
@endif
<div class="container-fluid d-flex justify-content-center mt-5 ">
    <div class="row" style="width: 80vw">
        <div class="col-4 d-flex flex-column justify-content-start align-items-center">
            <div class="row resetmp">
                <img style="width: 25em; height: 25em; border-radius:1rem;" src="{{ Storage::disk('public')->exists('items-image/'.$barang->foto) ? asset('storage/items-image/'.$barang->foto) : asset('img/DEFAULT-PLACEHOLDER-2213.png') }}" alt="">
            </div>
            @can('is_admin')
                <div class="row resetmp mt-5" style="width: 100%">
                    <div class="col resetmp d-flex justify-content-center align-items-center ">
                        <a href="/update-items/{{ $barang->id }}" class="btn-go edit" style="width: 6em">Edit</a>
                    </div>
                    <div class="col resetmp d-flex justify-content-center align-items-center ">
                        <form action="/delete-items/{{ $barang->id }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn-go delet" style="width: 6em">Delete</button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
        <div class="col-5 ">
            <h5 class="card-title nunito resetmp mx-2 mt-3 mb-4 " style="font-size: 2em; font-weight: 700;">{{ $barang->nama }}</h5>
            <div class="row mx-2 my-1 ">
                <div class="col-3 resetmp" style="height: 1.5rem">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height: 1rem;">Category <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start text-truncate" style="height: 1.5rem">
                    <a href="/category/{{ $barang->kategori_id }}" class="kateg"><p class="poppins" style="font-size: 1.3em; line-height:1rem;">{{ $barang->kategori->name }}</p></a>
                </div>
            </div>
            <div class="row mx-2 my-1 ">
                <div class="col-3 resetmp" style="height: 1.5rem">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Price <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start text-truncate" style="height: 1.5rem">
                    <h6 class="infotags poppins" style="font-size: 1.3em; line-height:1em; font-weight:600;">Rp {{ number_format($barang->harga, 0, ',', '.') }},00</h6>
                </div>
            </div>
            <div class="row mx-2 my-1" >
                <div class="col-3 resetmp" style="height: 1.5rem">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Stock <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start text-truncate" style="height: 1.5rem">
                    <h6 class="infotags poppins" style="font-size: 1.3em; line-height:1em; {{ ($barang->jumlah < 6) ? 'color:red;' : 'color:black;' }}">{{ $barang->jumlah == 0 ? 'Habis' : $barang->jumlah }}</h6>
                </div>
            </div>
            <div class="row mx-2 mt-4">
                <div class="col-3 resetmp" style="height: 1.5rem">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Description <span>:</span></h6>
                </div>
                <div class="col-12 resetmp d-flex justify-content-start mt-3">
                    <div class="scroll-bg">
                        <div class="scroll-div ">
                            <div class="scroll-object">
                                {!! nl2br(e($barang->deskripsi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="row bblac add-card" >
                <form class="col d-flex flex-column" action="/addtofacture/{{ $barang->id }}" method="POST">
                    @csrf
                    <h1 class="nunito pt-3 ps-2 mb-3" style="font-size: 1.8em; font-weight:800;">Item Info & Facture</h1>
                    <label class="ps-2 mb-2 nunito" for="count" style="font-size: 1.4em">Stock: {{ $barang->jumlah == 0 ? 'Habis!!' : $barang->jumlah }}</label>
                    <br>
                    <input class="extinp" type="number" id="count" name="count" value="{{ $value }}" min="1" max="{{ ($barang->jumlah == 0) ? 1 : $barang->jumlah }}">
                    @error('count')
                        <div class="row resetmp">
                            <div class="col-4 "></div>
                            <div class="col-8 resetmp px-2">
                                <div class="alert alert-danger nunito alerts resetmp bblac" style="font-size: 1.3em; text-align:center;">{{ $message }}</div>
                            </div>
                        </div>
                    @enderror
                    <br>
                    <div id="totals">
                        <h2 class="ps-2 mb-2 nunito" style="font-size: 1.4em; display:inline;">Subtotal:</h2>
                        <h2 id="subtotal">Rp {{ number_format($barang->harga, 0, ',', '.') }},00</h2>
                    </div>
                    <div class="d-grid gap-2 d-flex align-items-center" style="flex-grow: 1;">
                        <button type="submit" class="btn-go poppins" style="flex-grow: 1;" type="button">Add to facture</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/jquery.nice-number.js') }}"></script>
<script>
    $(function(){
        $('input[type="number"]').niceNumber({
            onIncrement: updateSubtotal,
            onDecrement: updateSubtotal
        });
    });

    function updateSubtotal($input, newValue, settings) {

        var itemPrice = {{ $barang->harga }};
        var subtotal = newValue * itemPrice;
        subtotal = subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

        $('#subtotal').text(subtotal);
    }

    $(document).ready(function(){
        setTimeout(() => {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

</script>
@endsection

