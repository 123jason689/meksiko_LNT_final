@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('/css/faktur.css') }}">
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
@endsection

@section('container')
<div class="container mt-5 px-5 facture-container">
    <div class="row head-row mt-1">
        <div class="col-4 facture-header">
            <h1>Invoice</h1>
            <p class="poppins" style="font-size: 1.3em">Date: {{ date('l, d-m-Y') }}</p>
        </div>
        <div class="col-4 facture-header">
            <h2>Invoice Number:</h2>
            <p class="poppins" style="font-size: 1.3em">{{ $noinv }}</p>
        </div>
        <div class="col-4">
            <div id="brand">
                <div id="logocont" class="">
                    <img src="{{ asset('img/main-logo.png') }}" alt="Logo mexico" id="mainlogo" class="">
                </div>
                <h1 class="protest-g">Meksiko</h1>
            </div>
        </div>
    </div>
    <div class="row head-row">
        <div class="col facture-customer">
            <h1>Customer Information</h1>
            <p class="resetmp"><span style="font-weight: 700">Name:</span> {{ $user->name }}</p>
            <p class="resetmp"><span style="font-weight: 700">Email:</span> {{ $user->email }}</p>
            <p class="resetmp"><span style="font-weight: 700">Address:</span> {{ $address }}</p>
            <p class="resetmp"><span style="font-weight: 700">Postal Code:</span> {{ $kodepos }}</p>
            <p class="resetmp"><span style="font-weight: 700">Phone Number:</span> +62 {{ $user->phone_number }}</p>
        </div>
    </div>
    <div class="row facture-items head-row">
        <div class="col">
            <h2>Items</h2>
            <table style="width:100%;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                @if (! empty($barangs))
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr>
                                <td>{{ $barang->nama }}</td>
                                <td>Rp {{ number_format($barang->harga, 0, ',', '.') }},00</td>
                                <td>{{ $barang['pivot']['frequency']}}</td>
                                <td>Rp {{ number_format($barang->harga * $barang['pivot']['frequency'], 0, ',', '.') }},00</td>
                            </tr>
                        @endforeach
                    </tbody>

                @else
                    <h1><i class="bi bi-cart-x"></i> No items!</h1>

                @endif
            </table>
        </div>
    </div>
    <div class="row facture-total d-flex justify-content-between">
        <div class="col-3">
            <h2>Total</h2>
            <p class="text-truncate">Rp {{ number_format(collect($barangs)->sum(fn($barang) => $barang->harga * $barang['pivot']['frequency']), 0, ',', '.') }},00</p>
        </div>
        <form action="/download-faktur" target="_self" method="POST" class="col-3 d-flex justify-content-center align-items-center">
            @csrf
            <input type="hidden" name="noinv"  value="{{ $noinv }}">
            <input type="hidden" name="kodepos"  value="{{ $kodepos }}">
            <input type="hidden" name="user"  value="{{ $user->id }}">
            <input type="hidden" name="address" value="{{ $address }}">
            @foreach ($barangs as $barang)
            <input type="hidden" name="barangid[]"  value="{{ $barang->id }}">
            @endforeach
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="fakturid" value="{{ $fakturid }}">
            <button type="submit" class="btn-go">Download Facture</button>
        </form>
    </div>
</div>

@endsection
