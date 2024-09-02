@extends('layout.index')

@section('links')
<link rel="stylesheet" href="{{ asset('css/show-barang.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.nice-number.css') }}">
<link rel="stylesheet" href="{{ asset('/css/faktur.css') }}">
@endsection

@section('container')
<div hidden class="alert alert-success nunito" style="font-weight: 600; font-size:1.2em; position: absolute; width:100%;">
    <p class="resetmp"><i class="bi bi-bag-check-fill"></i> Item deleted successfully !</p>
</div>

<a href="/user-faktur" class="history resetmp d-flex flex-column justify-content-center align-items-center">
    <i class="bi bi-clock-history resetmp"></i>
    <h1 class="resetmp ">History</h1>
</a>

<form class="container mt-1 px-5 facture-container submition" action="/update-faktur" method="POST">
    @csrf
    <div class="row mt-2 headers">
        <div class="col">
            <h1 style="font-size: 1.5em"><i class="bi bi-box-seam-fill"></i> Items</h1>
        </div>
    </div>
    <div class="row facture-items">
        <div class="col resetmp scrollable px-4">
            @if (! $barangs->isEmpty())
                @foreach ($barangs as $barang)
                    <div class="row card-item px-3 py-4 my-4" id="cardeach{{ $barang->id }}">
                        <div class="col-1">
                            <input type="checkbox" class="cbox" name="barangid[{{ $barang->id }}]" value="{{ $barang->id }}" id="checkeach{{ $barang->id }}" checked>
                        </div>
                        <div class="col-4 resetmp me-5 img-col">
                            <img class="" style="width: inherit; height:inherit;" src="{{ Storage::disk('public')->exists('items-image/'.$barang->foto) ? asset('storage/items-image/'.$barang->foto) : asset('img/DEFAULT-PLACEHOLDER-2213.png') }}" alt="A photo of {{ $barang->nama }}">
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col">
                                    <h2 class="nunito text-truncate" style="font-size: 1.5em; font-weight:400">{{ $barang->nama }}</h2>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <h2 class="nunito" id="" style="font-size: 1.2em; font-weight:800">Rp {{ number_format(($barang->harga), 0, ',', '.') }},00</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h2 class="nunito" style="font-size: 1.2em; font-weight:400">Stock: {{ $barang->jumlah }} left</h2>
                                </div>
                            </div>
                            <div class="row mt-2 mb-1">
                                <div class="col">
                                    <h2 class="nunito" style="font-size: 1em; font-weight:400; color:rgba(128, 128, 128, 0.717);">Qty:</h2>
                                </div>
                            </div>
                            <div class="row mb-3 d-flex flex-row justify-content-start">
                                <input class=" {{ ($barang->pivot->frequency >= $barang->jumlah) ? 'reds' : '' }} incdec countss" type="number" id="counteach{{ $barang->id }}" name="count[{{ $barang->id }}]" value="{{ $barang->pivot->frequency }}" min="1" max="{{ ($barang->jumlah == 0) ? 1 : $barang->jumlah }}">
                                <i class="bi bi-trash3-fill ms-2 trasher" id="trasheach{{ $barang->id }}"></i>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h2 class="nunito" style="font-size: 1.2em; font-weight:700">Subtotal: <span id="eachtotal{{ $barang->id }}">Rp {{ number_format(($barang->harga * $barang->pivot->frequency), 0, ',', '.') }},00</span> </h2>
                                </div>
                            </div>
                        </div>
                        <div class="trash">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                @endforeach
            @else
                <h1 class="nunito py-5 my-5"><i class="bi bi-cart-x"></i> No items!</h1>
            @endif
        </div>
    </div>
    <div class="row totals pt-1">
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1"  style="width:8em;">Address</span>
            <input type="text" name="address" class="form-control" placeholder="Destination address" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        @error('address')
            <div class="resetmp d-flex justify-content-center ">
                <div class="col-8 resetmp px-2 ">
                    <div class="alert alert-danger nunito alerts resetmp " style="font-size: 1.7vw; text-align:center;">{{ $message }}</div>
                </div>
            </div>
        @enderror
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1" style="width:8em;">Postal Code</span>
            <input type="number" name="kodepos" class="form-control" placeholder="Destination Postal Code" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        @error('kodepos')
            <div class="resetmp d-flex justify-content-center" >
                <div class="col-8 resetmp px-2 ">
                    <div class="alert alert-danger nunito alerts resetmp " style="font-size: 1.7vw; text-align:center;">{{ $message }}</div>
                </div>
            </div>
        @enderror
    </div>
    <div class="row d-flex justify-content-between mt-3">
        <div class="col-3">
            <h2>Total</h2>
            <p class="nunito" id="subtotal" style="font-weight: 800; font-size:1.2em;">Rp {{ number_format($user->barangs->sum(fn($barang) => $barang->harga * $barang->pivot->frequency), 0, ',', '.') }},00</p>
        </div>
        <div class="col-3 d-flex justify-content-end align-items-center">
            <input type="hidden" name="page" id="page" value="default">
            <button type="button" class="btn-go" id="dwnbtn" data-bs-toggle="modal" data-bs-target="#Modal">Print Facture</button>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Are You Sure <i class="bi bi-exclamation-square"></i></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    By clicking the confirm button below, you will <span style="color:crimson;">complete the purchase</span> of the items in your cart.
                    The invoice will be printed and can be downloaded after confirmation.
                    <br>
                    <br>
                    <h5>Do you wish to proceed ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-go" data-bs-dismiss="modal" style="background-color: red;">Close</button>{{-- maaf inline, file nya jauh --}}
                    <button type="submit" class="btn-go">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/jquery.nice-number.js') }}"></script>
<script>

    // const myModal = document.getElementById('.modal')
    // const myInput = document.getElementById('#dwnbtn')

    // myModal.addEventListener('shown.bs.modal', () => {
    //     myInput.focus()
    // })

    $(function(){
        $('input[type="number"].incdec').niceNumber({
            onIncrement: updateSubtotal,
            onDecrement: updateSubtotal
        });
    });

    let $barangs = @json($barangs);
    // let $stTotal = parseInt({{$user->barangs->sum(fn($barang) => $barang->harga * $barang->pivot->frequency)}});
    // $curtotal = $stTotal;
    function updateSubtotal($input, newValue, settings) {
        // console.log($input);
        let inptID = $input.attr('id');
        let barangId = inptID.replace('counteach', '');
        let itemPrice;
        $barangs.forEach(element => {
            if(element.id == barangId){
                itemPrice = parseInt(element.harga);
            }
        });

        let subtotal = newValue * itemPrice;
        subtotal = subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

        $('#eachtotal'+ barangId).text(subtotal);
        updateTotal();
    }

    // function upgradeTotal(add, sign){
    //     if(sign === '-'){
    //         $stTotal -= add
    //     } else if(sign==='+'){
    //         $stTotal += add;
    //     }

    //     $('#subtotal').text($stTotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }))
    // }

    function updateTotal(){
        let stotal = 0;
        $barangs.forEach(element => {
            $theid = element.id;
            // console.log(stotal);
            if($('#checkeach'+$theid).is( ":checked" )){
                let freq = $('#counteach'+$theid).val();
                stotal += parseInt(freq)*parseInt(element.harga);
                // console.log(stotal);
            }
        });

        $('#subtotal').text(stotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
    }

    $(document).ready(function(){
        setTimeout(() => {
            $('.alert').fadeOut('slow');
        }, 5000);

        $('.btn-go').on('click',function(){
            $('#page').val('faktur');
        });

        // Untuk update setiap user click tag a lainnya
        $('a').on('click', function(e){
            $('#page').val(e.target.href);
            console.log(e.target.href);

            let formData = $('.submition').serialize();

            $.ajax({
                type: 'POST',
                url: '/update-faktur',
                data: formData,
            });
        });

        $('.cbox').on('click', updateTotal);

        // flagging biar gak nabrak request nya
        let submitedflag = false;

        $('.submition').on('submit', function(){
            submitedflag = true;
        });

        $('.trasher').on('click', function(e){
            let id = this.id;
            let barangid = id.replace('trasheach', '');
            $('#cardeach'+barangid).remove();
            $.ajax({
                type: 'GET',
                url: '/remove-barang-faktur/' + barangid +  '/' + {{ auth()->user( )->id }},
                success: function() {
                    console.log('Item delete was successful !');
                    updateTotal();
                    $('.alert').show();
                    setTimeout(() => {
                        $('.alert').fadeOut('slow');
                    }, 5000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Request failed. Error:', errorThrown);
                }
            });
        });




        $(window).on('beforeunload', function(){
            if(!submitedflag){
                if (performance.navigation.type == 1) {
                    $('#page').val('/faktur');
                    // console.log('/faktur');
                    let formData = $('.submition').serialize();
                    // console.log(formData);

                    $.ajax({
                        type: 'POST',
                        url: '/update-faktur',
                        data: formData,
                    });
                } else {
                    console.info("Leaving Page");
                }
            }
        })


    });


</script>
@endsection
