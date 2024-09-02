<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\barang;
use App\Models\kategori;
use App\Models\datafaktur;
use App\Models\data_faktur;
use Error;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

use Illuminate\Auth\Events\Validated;
use PhpOption\None;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

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
        $barangss = collect();
        foreach ($user->barangs as $barang) {
            if(in_array($barang->id, $request->barangid)){
                $barangss->push($barang);
            }
        }

        $noinv = 'INV-' . date('Ymd') . '-' . str_pad(rand(0, 999), 3, "0", STR_PAD_LEFT) . sprintf('%04d', $user->id);

        $date = date('l, d-m-Y');

        // $jsondata = $barangss->map(function($barang){
        //     return $barang->toJson();
        // });

        // $stringjson = $jsondata->toJson();

        $stringjson = $barangss->map(function($barang){
            return $barang->toArray();
        })->toJson();


        $fakturs = datafaktur::create([
            'user_id' => $user->id,
            'date' => $date,
            'nomor_invoice' => $noinv,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'address' => $request->address,
            'kodepos' => $request->kodepos,
            'user_phone_number' => $user->phone_number,
            'data_item_json' => $stringjson,
        ]);

        return view('faktur', [
            'title' => 'Meksiko - Facture',
            'page' => 'faktur',
            'user' => $user,
            'barangs' => $barangss,
            'noinv' => $noinv,
            'address' => $request->address,
            'kodepos' => $request->kodepos,
            'date' => $date,
            'fakturid' => $fakturs->id,
            // 'barangs' =>
        ]);
    }

    public function showfaktur(){
        $user = auth()->user();
        if(!$user){
            return redirect()->route('login');
        }
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

    public function preventOrderOverflow(User $user){
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
        if(is_null($request->barangid)){
            return redirect('/items')->with('nullcart', "You haven't add any item to your cart yet");
        }

        foreach ($request->barangid as $val) {
            $user->barangs()->updateExistingPivot($val, [
                'frequency' => $request->count[$val]
            ]);
        }

        if($request->page == 'faktur'){
            $request->validate([
                'address' => 'required',
                'kodepos' => 'required',
            ]);
        }


        $this->preventOrderOverflow($user);

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
        $fakturs = $user->datafakturs()->get();
        $faktur = null;
        foreach ($fakturs as $fk ) {
            if($fk->id == $request->fakturid){
                $faktur = $fk;
            }
        }


        $barangs = $this->jsonparsefaktur($faktur);
        // $barangs = barang::find($request->barangid);
        $barangss = collect();
        foreach ($barangs as $barang) {
            if(in_array($barang->id, $request->barangid)){
                $barangss->push($barang);
            }
        }
        // error_log($barangss);
        // if($request->pagetype == 'prev'){
            $totals = $barangss->sum(fn($barang) => $barang->harga * $barang->pivot['frequency']);
        // } else{
            // $totals = $barangss->sum(fn($barang) => $barang->harga * $barang->pivot->frequency);
        // }

        $data = [
            'title' => 'Facture-' . $request->noinv,
            'barangs' => $barangss,
            'username' => $user->name,
            'useremail' => $user->email,
            'userphone' => $user->phone_number,
            'noinv' => $request->noinv,
            'address' => $request->address,
            'kodepos'=> $request->kodepos,
            'totals' => $totals,
            'tax' => 10,
            'date' => $request->date,
        ];
        set_time_limit(0);
        // $html = view('print-faktur', $data)->render();
        // file_put_contents(public_path('test.html'), $html);
        // $pdf = Pdf::loadHtml($html);
        // return $pdf->stream('invoice-'.$request->noinv.'.pdf');

        $pdf = Pdf::loadView('print-faktur', $data);
        return $pdf->download('invoice-'.$request->noinv.'.pdf');
        // return view('print-fakturprev', $data);
    }

    public function removebarang(barang $barang, User $user){
        // error_log($user);
        $user->barangs()->detach($barang->id);
        return response()->json(['success'=>true]);
    }

    public function previewFaktur($datafaktur){
        $faktur = datafaktur::find($datafaktur);

        $user = User::find($faktur->user_id);
        // $coll = collection
        $barangs = $this->jsonparsefaktur($faktur);

        foreach ($barangs as $barang) {
            error_log($barang);
        }

        // $barangs = barang::hydrate($barangss);

        return view('fakturprev', [
            'title' => 'Meksiko - Facture',
            'page' => 'faktur',
            'user' => $user,
            'barangs' => $barangs,
            'noinv' => $faktur->nomor_invoice,
            'address' => $faktur->address,
            'kodepos' => $faktur->kodepos,
            'date' => $faktur->date,
            'fakturid' => $faktur->id,
            // 'barangs' =>
        ]);
    }

    public function userFaktur(){
        $user = auth()->user();

        return view('history', [
            'title' => 'Meksiko - History',
            'page' => 'faktur',
            'fakturs' => $user->datafakturs,
        ]);
    }


    public function jsonparsefaktur($faktur){
        $barangss = json_decode($faktur->data_item_json, true);
        $barangs = array_map(function ($item) {
            $barang = new barang; // replace with your actual Barang model namespace
            foreach ($item as $key => $value) {
                $barang->$key = $value;
            }
            return $barang;
        }, $barangss);
        return $barangs;
    }
}
