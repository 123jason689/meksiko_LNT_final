<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class datafaktur extends Model
{
    use HasFactory;

    protected $fillable = [
		'user_id',
        'date',
        'nomor_invoice',
        'user_name',
        'user_email',
        'address',
        'kodepos',
        'user_phone_number',
        'data_item_json',
	];

    public function theuser(){
        return $this->belongsTo(User::class);
    }
}
