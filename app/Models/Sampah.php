<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    protected $table = 'sampah';
    protected $primaryKey = 'id';
    protected $fillable = ['tps_id', 'volumeSampah', 'tahun'];

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id', 'id');
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
