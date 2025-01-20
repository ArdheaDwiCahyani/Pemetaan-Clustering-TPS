<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    protected $table = 'sampah';
    protected $primaryKey = 'id';
    protected $fillable = ['tps_id', 'volumeSampah', 'tahun'];

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id');
    }

    //hitung rata-rata jarak (accessor)
    public function getRataRataJarakAttribute()
    {
        $tps = $this->tps;

        if ($tps) {
            $average = $tps->jarakKeTujuan()->avg('jarak'); // dihitung berdasarkan tps asal yang sama
            return number_format($average, 2, '.', ''); // membuat format 2 angka di belakang koma secara konsisten
        }
        return null;
    }
}
