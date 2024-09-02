@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.nice-number.css') }}">
@endsection

@section('container')
@if(session('alert'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em; position: absolute; width:100%;">
        <i class="bi bi-bag-check-fill"></i> {{ session('alert') }}
    </div>
@endif
@if(session('imgerror'))
    <div class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em;">
        <i class="bi bi-cart-x"></i> {{ session('alert') }}
    </div>
@endif
<form action="/update-items/{{ $barang->id }}" method="POST" enctype="multipart/form-data" class="container-fluid d-flex justify-content-center mt-5 ">
    @csrf
    @method('PATCH')
    <div class="row" style="width: 80vw">
        <div class="col-4 d-flex justify-content-center align-items-start ">
            <div class="">
                <h2 class="nunito" style="font-weight: 700">Update Image ONLY!!:</h2>
                <small style="color: rgba(128, 128, 128, 0.36)">Ignore if don't want to change</small>
                <div class="input-group mb-3">
                    <input type="file" name="image" class="form-control"  id="inputFile">
                </div>
                <div class="d-flex justify-content-center align-items-center" id="preview" style="overflow: hidden; background-color:rgb(205,205,205); height:20vw; width:20vw;">
                    {{-- placeholder for image to be previewd --}}
                    <img style="width: inherit; height: inherit; border-radius:1rem;" src="{{ Storage::disk('public')->exists('items-image/'.$barang->foto) ? asset('storage/items-image/'.$barang->foto) : asset('img/DEFAULT-PLACEHOLDER-2213.png') }}" alt="a photo of {{ $barang->nama }}">
                </div>
            </div>
        </div>
        <div class="col py-3 pb-4 px-4 bblac" style="background-color:beige; border-radius:3em;">
            <div class="d-flex align-items-center">
                <h1 class="nunito" style="display: inline">Name :</h1>
                <input type="text" name="nama" value="{{ $barang->nama }}" placeholder="Name of item" class="card-title nunito resetmp mx-2 mt-3 mb-4 " style="font-size: 2em; font-weight: 700; display:inline;">
            </div>
            <div class="row mx-2 my-3 d-flex align-items-center ">
                <div class="col-3 resetmp">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height: 1rem;">Category <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start text-truncate" >
                    <div class="input-group">
                        @if (empty($kategoris))
                            <div class="kateg">
                                <small class="poppins">No categories found! <a href="/addCategory">create a new one</a></small>
                            </div>
                        @else
                            <select class="form-select" id="inputGroupSelect01" name="kategori_id">
                                <option>Choose...</option>
                                @foreach ($kategoris as $kategori)
                                    @if ($kategori->id == $barang->kategori_id)
                                    <option selected value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                    @else
                                    <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                      </div>
                </div>
            </div>
            <div class="row mx-2 my-3 d-flex align-items-center ">
                <div class="col-3 resetmp">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Price <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start align-items-center text-truncate" style="font-size: 1.3em; line-height:1em; font-weight:600;">
                    Rp <div class="input-group infotags poppins"><input value="{{ $barang->harga }}" type="number" name="price" style="font-weight:600;" class="form-control" placeholder="Price" aria-label="Username" aria-describedby="basic-addon1"></div>,00
                </div>
            </div>
            <div class="row mx-2 my-3 d-flex align-items-center " >
                <div class="col-3 resetmp" >
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Stock <span>:</span></h6>
                </div>
                <div class="col-9 resetmp d-flex justify-content-start text-truncate">
                    <input type="number" value="{{ $barang->jumlah }}" min="1" class="incdec" name="count" id="count" >
                </div>
            </div>
            <div class="row mx-2 mt-4 d-flex align-items-center ">
                <div class="col-3 resetmp">
                    <h6 class="poppins resetmp d-flex justify-content-between pe-1" style="font-size: 1.3em; line-height:1em;">Description <span>:</span></h6>
                </div>
                <div class="col-12 resetmp d-flex justify-content-start mt-3">
                    <div class="input-group">
                        <textarea name="deskripsi" class="form-control" aria-label="With textarea" style="resize: none; height:30vh">{{ $barang->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <button type="submit" class="btn-go">Update</button>
            </div>
        </div>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/jquery.nice-number.js') }}"></script>
<script>
    $(function(){
        $('input[type="number"].incdec').niceNumber();
    });

    $(document).ready(function(){
        setTimeout(() => {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

    $('#inputFile').change(function(){
        var file = this.files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            $('#preview').html('<img src="' + reader.result + '" style="width:100%; height:100%; object-fit:cover;">');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            $('#preview').html('');
        }
    });

</script>
@endsection
