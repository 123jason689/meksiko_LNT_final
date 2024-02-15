<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Validated;
use Psy\Readline\Hoa\Console;
use PDF;

use function PHPUnit\Framework\isEmpty;

class fakturController extends Controller
{
    public function addbarang(barang $barang, Request $request){
        $user = $request->user();
        $request->validate([
            'count' => 'required|min:1|max:' . $barang->jumlah
        ]);

        $additional = [
            'frequency'=> $request->count,
        ];

        if ($user->barangs->contains($barang->id)) {
            // The user already has the barang, handle accordingly
            $user->barangs()->updateExistingPivot($barang->id, $additional);
            return redirect('/items/' . $barang->id)->with('alert', 'Item info updated!');
        } else {
            $user->barangs()->attach($barang->id, $additional);
            return redirect('/items/' . $barang->id)->with('alert', 'Items successfully added!');
        }
    }

    public function printfaktur(Request $request){
        $user = auth()->user();
        $barangss = [];
        foreach ($user->barangs as $barang) {
            if(in_array($barang->id, $request->barangid)){
                array_push($barangss,$barang);
            }
        }

        $noinv = 'INV-' . date('Ymd') . '-' . str_pad(rand(0, 999), 3, "0", STR_PAD_LEFT);

        return view('faktur', [
            'title' => 'Meksiko - Facture',
            'page' => 'faktur',
            'user' => $user,
            'barangs' =>  $barangss,
            'noinv' => $noinv,
            'address' => $request->address,
            'kodepos' => $request->kodepos,
            // 'barangs' =>
        ]);
    }

    public function showfaktur(){
        $user = auth()->user();
        $barang = $user->barangs;
        if(empty($barang)){
            $barang = [];
        }

        $this->preventOrderOverflow($user);

        return view('show-faktur', [
            'title' => 'Meksiko - Facture',
            'page' => 'faktur',
            'user' => $user,
            'barangs'=> $barang,
        ]);
    }

    public function preventOrderOverflow(){
        $user = auth()->user();
        $barang = $user->barangs;
        $marked = [];
        foreach ($barang as $brg) {
            if($brg->jumlah < $brg->pivot->frequency){
                $additional = [
                    'frequency' => $brg->jumlah,
                ];
                array_push($marked, $brg->id);
                // error karena gak ambil dari request karena gak ada request
                // ambil dari auth masih bisa tapi di tandain error sama intelephense
                $user->barangs()->updateExistingPivot($brg->id, $additional);
            }
        }
        return $marked;
    }
    // untuk update data count setiap user pergi dari page show-faktur
    public function updateOutOfBound(Request $request){
        $user = $request->user();

        foreach ($request->barangid as $val) {
            $user->barangs()->updateExistingPivot($val, [
                'frequency' => $request->count[$val]
            ]);
        }

        if($request->page == 'faktur'){ //kembali tidur jam dua besok kelas pagi karena satu hal menyesatkan ini
            //dari tadi bingung kenapa request on window before unload tidak bisa di pake
            // dari kemaren udah kotak katik, ternyata gara gara laravel ->validate throw error
            // gak bisa di tangkep kalo pake request dari ajax, solusinya bikin if wkwkkwkwkwkwkw
            $request->validate([
                'address' => 'required',
                'kodepos' => 'required',
            ]);
        }


        $this->preventOrderOverflow();

        if($request->page == 'faktur'){
            // kurangi jumlah barang yang sudah masuk faktur
            foreach ($request->barangid as $barangid) {
                $barang = barang::find($barangid);
                $barang->decrement('jumlah', $user->barangs->where('id', $barangid)->first()->pivot->frequency);
                $user->barangs()->detach($barangid);
            }

            return $this->printfaktur($request);
        } else{
            return redirect($request->page);
        }

    }


    public function downloadFaktur(Request $request){
        $user = User::find($request->user);
        // $barangs = barang::find($request->barangid);
        $barangss = [];
        foreach ($user->barangs as $barang) {
            if(in_array($barang->id, $request->barangid)){
                array_push($barangss,$barang);
            }
        }

        $totals = collect($barangss)->sum(fn($barang) => $barang->harga * $barang->pivot->frequency);

        $data = [
            'title' => 'Facture-' . $request->noinv,
            'barangs' => $barangss,
            'user' => $user,
            'noinv' => $request->noinv,
            'address' => $request->address,
            'kodepos'=> $request->kodepos,
            'totals' => $totals,
            'tax' => 10,
        ];

        // $pdf = PDF::loadView('print-faktur', $data);
        // return $pdf->download('invoice-'.$request->noinv.'.pdf');
        //sudah jam 3.20 AM setelah bingung pusing dan stress blade html tidak bisa di download ke pdf
        // berhenti pada return terakhir di donload()
        // web loading terus menerus tanpa henti sampai laravel shutdown karena melebihi 60 detik
        return view('print-faktur', $data);
        // bisa di SS aja :)
    }

    public function removebarang(barang $barang, User $user){
        // error_log($user);
        $user->barangs()->detach($barang->id);
        return response()->json(['success'=>true]);
    }


}
