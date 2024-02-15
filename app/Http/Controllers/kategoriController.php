<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class kategoriController extends Controller
{
    public function createPage(){
        return view('create-kategori', [
            'title' => 'Meksiko - Create Category',
            'page' => 'categories',
        ]);
    }

    public function categoryCreate(Request $request){

        $request->validate([
            'kategori' => 'required|min:2|max:255',
        ]);

        if(kategori::where('name', $request->kategori)->exists()){
            throw ValidationException::withMessages([
                'kategori' => ['There already exist a category with that name.'],
            ]);
        }else{
            kategori::create([
                'name' => $request->kategori,
            ]);
        }


        return redirect('/create-category')->with('alert', 'Category successfully created!');
    }

    public function showcategories(){
        $kategoris = kategori::all()->sortBy('name');
        return view('show-kategori', [
            'title' => 'Meksiko - Categories',
            'page' => 'categories',
            'kategoris' => $kategoris,
        ]);
    }

    public function category(kategori $kategori){
        $barangs = $kategori->barang()->orderBy('nama')->get();

        return view('kategori-barang', [
            'title' => 'Meksiko - ' . $kategori->name . ' - ' . 'Category',
            'page' => 'categories',
            'kategori' => $kategori,
            'barangs' => $barangs
        ]);
    }

    public function destroyss(kategori $kategori){
        $kategori->delete();

        return redirect('/categories')->with('alert', 'Item sucessfully deleted!');
    }
}
