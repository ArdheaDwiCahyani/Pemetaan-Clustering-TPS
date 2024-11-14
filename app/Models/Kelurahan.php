<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class Kelurahan extends Model
{
    protected $table = 'kelurahans';
    protected $primaryKey = 'id';

    protected $fillable = ['namaKelurahan', 'kecamatan_id'];
    
    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function tps() {
        return $this->hasMany(Tps::class, 'kelurahans_id');
    }
}
