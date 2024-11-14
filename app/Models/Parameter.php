<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tps;

class Parameter extends Model
{
    protected $table = 'params';
    protected $primaryKey = 'id';
    protected $fillable = ['namaParameter'];


    public function sampah()
    {
        return $this->belongsToMany(Sampah::class, 'tpsParameter', 'params_id', 'sampah_id')
            ->withPivot('nilai_parameter')
            ->wherePivot('entity', 'sampah');
    }

    public function tps()
    {
        return $this->belongsToMany(Tps::class, 'tpsParameter', 'params_id', 'tps_id')
            ->withPivot('nilai_parameter')
            ->wherePivot('entity', 'tps');
    }
}
