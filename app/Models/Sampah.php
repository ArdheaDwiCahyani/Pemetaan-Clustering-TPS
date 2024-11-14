<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    protected $table = 'sampah';
    protected $primaryKey = 'id';
    protected $fillable = ['tps_id', 'tahun'];

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id');
    }

    public function parameter()
    {
        return $this->belongsToMany(Parameter::class, 'tpsParameter')
            ->withPivot('nilai_parameter', 'entity');
    }

    //ambil jarak ke tpa secara otomatis menggunakan accessor
    public function getJarakTpaAttribute()
    {
        //mencari parameter jarak ke tpa untuk tps terkait
        $jarak_tpa = $this->tps->parameter()
            ->wherePivot('entity', 'tps')
            ->where('namaParameter', 'Jarak ke TPA')
            ->first();
        //mengembalikan nilai pivot 
        return $jarak_tpa ? $jarak_tpa->pivot->nilai_parameter : null;
    }

    //hitung rata-rata jarak
    public function getRataRataJarakAttribute()
    {
        $tps = $this->tps;

        if ($tps) {
            return round($tps->jarakKeTujuan()->avg('jarak'), 2);
        }

        return null;
    }
}
