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
<div class="container mt-4 ">
    <div class="row mb-2">
        <div class="col">
            <h1><i class="bi bi-box-seam-fill"></i> Categories</h1>
        </div>
    </div>
    <div class="row bblac" style=" overflow:hidden; border-radius: 2em; height: 73vh;">
        <ul class="my-3 " style="padding: 1vw 3vw 1vw 3vw ; overflow:hidden; overflow-y:scroll; height: inherit;">
            @can('is_admin')
                <li style="font-size: 3em; overflow:hidden; border-bottom:2px solid rgba(128, 128, 128, 0.507); padding-bottom: 0.3em;" class="my-4 d-flex justify-content-center align-items-center"><a class=" btn-go card-add" href="/create-category" style=" text-decoration:none; color:black; padding:0.15em 0.7em 0.15em 0.7em;">+ Add Category</a></li>
            @endcan
            @if (!$kategoris->isEmpty())
                @foreach ( $kategoris as $kategori )
                    <li style="font-size: 3em; overflow:hidden; border-bottom:2px solid rgba(128, 128, 128, 0.507); padding-bottom: 0.3em;" class=" row my-4 d-flex justify-content-center align-items-center ">
                        <a class="col btn-go" href="/category/{{ $kategori->id }}" style=" text-decoration:none; color:black; padding:0.15em 0.7em 0.15em 0.7em;">{{ $kategori->name }}</a>
                        @can('is_admin')
                        <form action="/delete-category/{{ $kategori->id }}" method="POST" class="col ms-5">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-go delet " style=" text-decoration:none; color:black; padding:0.15em 0.7em 0.15em 0.7em; font-size: 1em; display:inline; width:100%;">Delete</button>
                        </form>

                            {{-- <a class="col btn-go delet ms-5" href="/delete-category/{{ $kategori->id }}" style=" text-decoration:none; color:black; padding:0.15em 0.7em 0.15em 0.7em; font-size: 1em;">Delete</a> --}}
                        @endcan
                    </li>
                @endforeach
            @else
                <h1 class="nunito d-flex align-items-center justify-content-center" id="nofound">Couldn't Find Anything<i class="bi bi-emoji-frown-fill ms-5"></i></h1>
            @endif
        </ul>
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
