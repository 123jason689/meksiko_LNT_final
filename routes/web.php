<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\barangController;
use App\Http\Controllers\fakturController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\registerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home', [
        'title' => 'Meksiko - Home Page',
		'page'=> 'home',
    ]);
});

//Route::get('/categories', []);

Route::get('/login', [loginController::class,'login'])->middleware('guest')->name('login');
Route::POST('/login', [loginController::class, 'authenticate'])->middleware('guest');
Route::POST('/logout',[loginController::class, 'logout'])->middleware('auth');

Route::get('/register', [registerController::class, 'register'])->middleware('guest');
Route::POST('/register', [registerController::class, 'store'])->middleware('guest');

Route::get('/dashboard', []);


Route::get('/items', [barangController::class, 'items'])->middleware('auth');
Route::get('/items/{barang:id}', [barangController::class, 'showItem'])->middleware('auth');

Route::get('/create-items', [barangController::class, 'createPage'])->middleware('admin');
Route::POST('/create-items', [barangController::class, 'itemsCreate'])->middleware('admin');
Route::get('/update-items/{barang:id}', [barangController::class, 'updatePage'])->middleware('admin');
Route::PATCH('/update-items/{barang:id}', [barangController::class, 'itemsUpdate'])->middleware('admin');
Route::DELETE('/delete-items/{barang:id}', [barangController::class, 'destroyss'])->middleware('admin');
// Route::get('/manage', [adminController::class, 'managed']);

Route::get('/categories', [kategoriController::class, 'showcategories'])->middleware('auth');
Route::get('/category/{kategori:id}', [kategoriController::class, 'category'])->middleware('auth');
Route::get('/create-category', [kategoriController::class, 'createPage'])->middleware('admin');
Route::POST('/create-category', [kategoriController::class, 'categoryCreate'])->middleware('admin');
Route::DELETE('/delete-category/{kategori:id}', [kategoriController::class, 'destroyss'])->middleware('admin');

Route::POST('/addtofacture/{barang:id}', [fakturController::class, 'addbarang'])->middleware('auth');
Route::get('/faktur', [fakturController::class, 'showfaktur']);

Route::get('/preventOrderOverflow', [fakturController::class, 'preventOrderOverflow'])->middleware('auth');
Route::POST('/print-faktur', [fakturController::class, 'printfaktur'])->middleware('auth');
Route::POST('/update-faktur', [fakturController::class, 'updateOutOfBound'])->middleware('auth');
Route::get('/remove-barang-faktur/{barang:id}/{user:id}', [fakturController::class, 'removebarang'])->middleware('auth');
Route::POST('/download-faktur', [fakturController::class, 'downloadFaktur'])->middleware('auth');
Route::get('/preview-faktur/{datafaktur}', [fakturController::class, 'previewFaktur'])->middleware('auth');
Route::get('/user-faktur', [fakturController::class, 'userFaktur'])->middleware('auth');

Route::get('/manage', [adminController::class, 'showmanage'])->middleware('admin');
