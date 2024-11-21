<?php

namespace App\Http\Controllers;

use App\Models\Jarak;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Parameter;
use App\Models\Sampah;
use App\Models\Tps;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $jmlKecamatan = Kecamatan::count();
        $jmlKelurahan = Kelurahan::count();
        $jmlParameter = Parameter::count();
        $jmlTps = Tps::count();
        $jmlJarak = Jarak::count();
        $jmlSampah = Sampah::count();

        return view('dashboard', compact('jmlKecamatan', 'jmlKelurahan', 'jmlTps', 'jmlJarak', 'jmlSampah', 'jmlParameter'));
    }
}
