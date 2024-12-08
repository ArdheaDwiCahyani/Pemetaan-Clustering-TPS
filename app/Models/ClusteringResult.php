<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringResult extends Model
{
    use HasFactory;
    protected $table = 'clustering_results';

    protected $fillable = [
        'tps_id', 
        'normalized_volume', 
        'normalized_jarak', 
        'normalized_rata_rata_jarak', 
        'cluster', 
        'prioritas', 
        'tahun',
    ];

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id');  // Pastikan ada model Tps yang sesuai
    }

}
