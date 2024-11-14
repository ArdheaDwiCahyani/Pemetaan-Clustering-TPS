<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelurahan;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';
    protected $primaryKey = 'id';
    protected $fillable = ['namaKecamatan'];

    public function kelurahan() {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }
}
