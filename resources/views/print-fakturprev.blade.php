<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $noinv }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <style>
        body{
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            padding: 0;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .container{
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }
        .brand-section{
           background-color: #E0D958;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{
            padding: 16px;
            border: 1px solid gray;
        }
        .heading{
            font-size: 20px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        table td {
            vertical-align: middle !important;
            text-align: center;
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-right{
            text-align: end;
        }
        .w-20{
            width: 20%;
        }
        .float-right{
            float: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="brand-section">
            <div class="row">
                <div class="col-6">
                    <div id="brand">
                        <div id="logocont" class="">
                            <img src="{{ asset('img/main-logo.png') }}" alt="Logo mexico" id="mainlogo" class="">
                        </div>
                        <h1 class="protest-g">Meksiko</h1>
                    </div>
                </div>
                <div class="col-6">
                    <div class="company-details mt-2">
                        <p class="">Empowering Warehouse</p>
                        <p class="">Right From The Web</p>
                        <p class="">+62 812-696-969</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading text-truncate">Invoice No.: {{ $noinv }}</h2>
                    {{-- <p class="sub-heading">Tracking No. fabcart2025 </p> --}}
                    <p class="sub-heading text-truncate">Order Date: {{ $date }} </p>
                    <p class="sub-heading text-truncate">Email Address: {{ $user->email }} </p>
                </div>
                <div class="col-6">
                    <p class="sub-heading text-truncate">Full Name: {{ $user->name }} </p>
                    <p class="sub-heading">Address: {{ $address }} </p>
                    <p class="sub-heading text-truncate">Postal Code: {{ $kodepos }} </p>
                    <p class="sub-heading text-truncate">Phone Number: {{ $user->phone_number }} </p>
                    {{-- <p class="sub-heading">City,State,Pincode:  </p> --}}
                </div>
            </div>
        </div>

        <div class="body-section">
            <h3 class="heading text-truncate">Ordered Items</h3>
            <br>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="w-20 ">Price</th>
                        <th class="w-20">Quantity</th>
                        <th class="w-20">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                    <tr>
                        {{-- <p>{{ $barang }}</p> --}}
                        <td class="text-truncate">{{ $barang->nama }}</td>
                        <td class="text-truncate">Rp {{ number_format($barang->harga, 0, ',', '.') }},00</td>
                        <td class="text-truncate">{{ $barang['pivot']['frequency'] }}</td>
                        <td class="text-truncate">Rp {{ number_format($barang->harga * $barang['pivot']['frequency'], 0, ',', '.') }},00</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right pe-4">Sub Total</td>
                        <td> Rp {{ number_format($totals, 0, ',', '.') }},00</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right pe-4">Tax Total {{ $tax }}%</td>
                        <td> Rp {{ number_format(round(($totals*$tax)/100), 0, ',', '.') }},00</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right pe-4">Grand Total</td>
                        <td> Rp {{ number_format(round(($totals*($tax+100))/100), 0, ',', '.') }},00</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <h3 class="heading">Payment Status: Awaiting</h3>
            <h3 class="heading">Payment Mode: Cash on Delivery</h3>
        </div>

        <div class="body-section">
            <p>&copy; Copyright 20{{ date('y') }} - Meksiko. All rights reserved.</p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
