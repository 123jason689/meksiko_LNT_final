@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
@endsection

@section('container')
<div class="container mt-4 ">
    <div class="row mb-2">
        <div class="col">
            <h1><i class="bi bi-clock-history"></i> Facture History</h1>
        </div>
    </div>
    <div class="row bblac" style=" overflow:hidden; border-radius: 2em; height: 73vh;">
        <ul class="my-3 " style="padding: 1vw 3vw 1vw 3vw ; overflow:hidden; overflow-y:scroll; height: inherit;">
            @if (!$fakturs->isEmpty())
                @foreach ( $fakturs as $faktur )
                    <li style="font-size: 3em; overflow:hidden; border-bottom:2px solid rgba(128, 128, 128, 0.507); padding-bottom: 0.3em;" class=" row my-4 d-flex justify-content-center align-items-center ">
                        <a class="col btn-go" href="/preview-faktur/{{ $faktur->id }}" style=" text-decoration:none; color:black; padding:0.15em 0.7em 0.15em 0.7em;">{{ $faktur->nomor_invoice }}</a>
                    </li>
                @endforeach
            @else
                <h1 class="nunito d-flex align-items-center justify-content-center" id="nofound">Couldn't Find Anything<i class="bi bi-emoji-frown-fill ms-5"></i></h1>
            @endif
        </ul>
    </div>
</div>
@endsection
