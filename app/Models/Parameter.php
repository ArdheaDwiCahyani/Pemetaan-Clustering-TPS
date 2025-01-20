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

}
