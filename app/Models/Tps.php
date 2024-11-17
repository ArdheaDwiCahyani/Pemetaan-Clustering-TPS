<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parameter;

class Tps extends Model
{
    protected $table = 'tps';
    protected $primaryKey = 'id';
    protected $fillable = ['namaTPS', 'kelurahans_id', 'tahun', 'latitude', 'longitude'];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahans_id', 'id');
    }

    public function parameter()
    {
        return $this->belongsToMany(Parameter::class, 'tpsParameter', 'tps_id', 'params_id')
            ->withPivot('nilai_parameter', 'entity');
    }

    //relasi u/ parameter dg entitas tps
    public function parameterTps()
    {
        return $this->parameter()->wherePivot('entity', 'tps');
    }

    //relasi u/ parameter dg entitas sampah
    public function parameterSampah()
    {
        return $this->parameter()->wherePivot('entity', 'sampah');
    }

    public function sampah()
    {
        return $this->hasMany(Sampah::class);
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
