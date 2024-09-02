<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\kategori;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class barangController extends Controller
{
    public function items(){
        $items = barang::where('jumlah', '>', 0)->orderBy('nama')->get();
        $habis = barang::where('jumlah', '=', 0)->orderBy('nama')->get();
        return view('show-barang', [
            'title'=>'Meksiko - Items List',
			'page'=>'items',
            'barangs'=>$items,
            'habiss' => $habis
        ]);;
    }

    public function showItem(barang $barang){
        $val = 1;
        $barangcollection = auth()->user()->barangs;
        if($barangcollection->contains($barang->id)){
            $val = $barangcollection->find($barang->id)->pivot->frequency;
        }

        return view('barang', [
            'title' => 'Meksiko - ' . $barang->nama,
            'page' => 'items',
            'value' => $val,
            'barang' => $barang,
        ]);
    }

    public function updatePage(barang $barang){
        $kategoris = kategori::all();
        return view('update-barang',[
            'title' => 'Meksiko - Update Item',
            'page' => 'manage',
            'barang'=> $barang,
            'kategoris' => $kategoris,
        ]);
    }

    public function itemsUpdate(barang $barang, Request $request) {
        // Validate the request...
        $request->validate([
            'kategori_id'=> 'required',
            'nama' => 'required|max:225',
            'price'=> 'required|min:1',
            'count'=> 'required|min:1',
            'image'=> 'image|mimes:png,jpg,svg,jpeg|max:25600',
            'deskripsi'=> 'required|min:10|max:50000',
        ]);
        $haveFile = $request->hasFile('image');
        $img = $barang->foto;
        if($haveFile){
            $delet = Storage::delete('/public/items-image/' . $barang->foto);

            if(! $delet){
                return redirect('/update-items/' . $barang->id )->with('imgerror', 'One of your file failed while processing, please re-input the values!');
            }

            $filetype = $request->file('image')->getClientOriginalExtension();
            $filename = $barang->id . '-' . $this->bisuIfy($barang->nama) . '.' . $filetype;
            $request->file('image')->storeAs('/public/items-image', $filename);
            $img = $filename;
        } elseif(!$barang->nama==$request->nama && !$haveFile){
            $filetype = pathinfo($barang->foto, PATHINFO_EXTENSION);
            $filename = $barang->id . '-' . $this->bisuIfy($barang->nama) . '.' . $filetype;
            if (Storage::exists('public/items-image/' . $barang->foto)) {
                Storage::move('public/items-image/' . $barang->foto, 'public/items-image/' . $filename);

            } else {
                return redirect('/update-items/' . $barang->id )->with('imgerror', 'One of your file failed while processing, please re-input the values!');
            }
        }

        $barang->update([
            'kategori_id'=> $request->kategori_id,
            'nama'=> $request->nama,
            'harga'=> $request->price,
            'jumlah'=> $request->count,
            'foto'=> $img,
            'deskripsi'=> $request->deskripsi,
        ]);

        return redirect('/items')->with('alert', 'Item sucessfully updated!');
    }

    public function createPage(){
        $categories = kategori::all();
        return view('create-barang', [
            'title'=> 'Meksiko - Create',
            'page' => 'manage',
            'kategoris' => $categories,

        ]);
    }

    public function itemsCreate(Request $request){
        $request->validate([
            'kategori_id'=> 'required',
            'nama' => 'required|max:225',
            'price'=> 'required|min:1',
            'count'=> 'required|min:1',
            'image'=> 'required|image|mimes:png,jpg,svg,jpeg|max:25600',
            'deskripsi'=> 'required|min:10|max:50000',
        ]);

        $barangini = barang::create([
            'kategori_id'=> $request->kategori_id,
            'nama'=> $request->nama,
            'harga'=> $request->price,
            'jumlah'=> $request->count,
            'foto'=> 'untitled',
            'deskripsi'=> $request->deskripsi,
        ]);

        $filetype = $request->file('image')->getClientOriginalExtension();
        $filename = $barangini->id . '-' . $this->bisuIfy($barangini->nama) . '.' . $filetype;
        $request->file('image')->storeAs('/public/items-image', $filename);

        $barangini->update(['foto'=>$filename]);

        return redirect('/create-items')->with('alert', 'Item successfuly added !');

    }

    public function destroyss(barang $barang){
        $barang->delete();

        return redirect('/items')->with('alert', 'Item sucessfully deleted!');
    }

    // non public function for inside use
    function bisuIfy($str, $limit = 5) {
        preg_match_all('/[^aeiou\s]/i', $str, $matches);
        $chars = array_slice($matches[0], 0, $limit);

        if (empty($chars)) {
            preg_match_all('/[aeiou]/i', $str, $matches);
            $chars = array_slice($matches[0], 0, $limit);
        }

        return implode('', $chars);
    }

}
