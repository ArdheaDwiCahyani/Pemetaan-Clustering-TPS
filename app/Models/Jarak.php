<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jarak extends Model
{
    protected $table = 'jaraks';
    protected $primaryKey = 'id';
    protected $fillable = ['tps_asal_id', 'tps_tujuan_id', 'jarak'];

    public function tpsAsal() {
        return $this->belongsTo(Tps::class, 'tps_asal_id');
    }

    public function tpsTujuan() {
        return $this->belongsTo(Tps::class, 'tps_tujuan_id');
    }
}
