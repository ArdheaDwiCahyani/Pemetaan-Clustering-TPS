<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parameter;

class Tps extends Model
{
    protected $table = 'tps';
    protected $primaryKey = 'id';
    protected $fillable = ['namaTPS', 'kelurahans_id', 'jarakTPA', 'latitude', 'longitude'];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahans_id');
    }

    public function sampah()
    {
        return $this->hasMany(Sampah::class, 'tps_id');
    }

    public function jarakKeTujuan()
    {
        return $this->hasMany(Jarak::class, 'tps_asal_id');
    }

    public function jarakDariAsal()
    {
        return $this->hasMany(Jarak::class, 'tps_tujuan_id');
    }
}
