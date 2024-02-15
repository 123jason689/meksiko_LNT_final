<?php

namespace App\Models;

use App\Models\User;
use App\Models\kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class barang extends Model
{
    use HasFactory;

	protected $fillable=[
		'kategori_id',
		'nama',
		'harga',
		'jumlah',
		'foto',
        'deskripsi'
	];

	public function kategori(){
		return $this->belongsTo(kategori::class);
	}

    public function users(){
        return $this->belongsToMany(User::class, 'barangs_users')->withPivot('frequency');
    }
}
