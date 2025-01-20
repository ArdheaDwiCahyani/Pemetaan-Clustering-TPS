<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\sampahController;
use App\Http\Controllers\jarakController;
use App\Http\Controllers\kecamatanController;
use App\Http\Controllers\kelurahanController;
use App\Http\Controllers\parameterController;
use App\Http\Controllers\petaController;
use App\Http\Controllers\prosesClustering;
use App\Http\Controllers\prosesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\tpsController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/forgot-pw', 'showForgotPassword')->name('forgot-pw');
    Route::post('/reset-pw', 'sendResetLink')->name('reset-pw');
    Route::get('/password/reset/{token}', function (string $token) {
        return view('auth.reset-pw', ['token' => $token]);
    })->name('password.reset');
    Route::post('password/reset', 'resetPassword')->name('password.update');
    Route::get('send-email', 'index')->name('send-email');
});


Route::middleware(['first.visit'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

    Route::controller(kecamatanController::class)->prefix('kecamatan')->group(function () {
        Route::get('/', 'index')->name('kecamatan');
        Route::get('tambah', 'tambah')->name('kecamatan.tambah');
        Route::post('tambah', 'simpan')->name('kecamatan.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('kecamatan.edit');
        Route::post('edit/{id}', 'update')->name('kecamatan.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('kecamatan.hapus');
        Route::get('allKecamatan', 'allKecamatan')->name('allKecamatan');
    });

    Route::controller(kelurahanController::class)->prefix('kelurahan')->group(function() {
        Route::get('/', 'index')->name('kelurahan');
        Route::get('tambah', 'tambah')->name('kelurahan.tambah');
        Route::post('tambah', 'simpan')->name('kelurahan.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('kelurahan.edit');
        Route::post('edit/{id}', 'update')->name('kelurahan.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('kelurahan.hapus');
        Route::get('import', 'importForm')->name('kelurahan.import');
        Route::post('import', 'import')->name('kelurahan.import.simpan');
        Route::get('export', 'export')->name('kelurahan.export');
        Route::get('allKelurahan', 'allKelurahan')->name('allKelurahan');
        // Route::get('search', 'handleSearch')->name('kelurahan.search');
    });

    Route::controller(tpsController::class)->prefix('tps')->group(function() {
        Route::get('/', 'index')->name('tps');
        Route::get('tambah', 'tambah')->name('tps.tambah');
        Route::post('tambah', 'simpan')->name('tps.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('tps.edit');
        Route::post('edit/{id}', 'update')->name('tps.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('tps.hapus');
        Route::get('import', 'importForm')->name('tps.import');
        Route::post('import', 'import')->name('tps.import.simpan');
        Route::get('export', 'export')->name('tps.export');
        Route::get('allTps', 'allTps')->name('allTps');
    });

    Route::controller(jarakController::class)->prefix('jarak')->group(function() {
        Route::get('/', 'index')->name('jarak');
        Route::get('tambah', 'tambah')->name('jarak.tambah');
        Route::post('tambah', 'simpan')->name('jarak.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('jarak.edit');
        Route::post('edit/{id}', 'update')->name('jarak.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('jarak.hapus');
        Route::get('import', 'importForm')->name('jarak.import');
        Route::post('import', 'import')->name('jarak.import.simpan');
        Route::get('export', 'export')->name('jarak.export');
        Route::get('allJarak', 'allJarak')->name('allJarak');
    });

    Route::controller(sampahController::class)->prefix('sampah')->group(function() {
        Route::get('/', 'index')->name('sampah');
        Route::post('tambahTahun', 'addTahun')->name('sampah.addTahun');
        Route::post('hapusTahun', 'removeTahun')->name('sampah.removeTahun');
        Route::get('tambah', 'tambah')->name('sampah.tambah');
        Route::post('tambah', 'simpan')->name('sampah.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('sampah.edit');
        Route::post('edit/{id}', 'update')->name('sampah.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('sampah.hapus');
        Route::get('import', 'importForm')->name('sampah.import');
        Route::post('import', 'import')->name('sampah.import.simpan');
        Route::get('export', 'export')->name('sampah.export');
        Route::get('allSampah', 'allSampah')->name('allSampah');
    });

    Route::controller(parameterController::class)->prefix('parameter')->group(function() {
        Route::get('/', 'index')->name('parameter');
        Route::get('tambah', 'tambah')->name('parameter.tambah');
        Route::post('tambah', 'simpan')->name('parameter.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('parameter.edit');
        Route::post('edit/{id}', 'update')->name('parameter.tambah.update');
        Route::delete('id/{id}', 'hapus')->name('parameter.hapus');
        Route::get('allParams', 'allParams')->name('allParams');
    });

    Route::controller(prosesController::class)->prefix('proses')->group(function() {
        Route::get('/', 'showProses')->name('proses');
        // Route::get('/show-cluster', 'showCluster')->name('show.cluster');
        Route::post('/', 'showProses')->name('proses.cluster');
        Route::get('/show-replace/{tahun}', 'showProsesReplace')->name('show.replace');
        Route::get('/proses/cluster', 'processClustering')->name('proses.cluster2');
        Route::get('export/{tahun}', 'exportCluster')->name('hasil.export');
        Route::get('perform/{tahun}', 'performClustering')->name('perform');
    });

    Route::controller(petaController::class)->prefix('peta')->group(function() {
        Route::get('/', 'indexPeta')->name('peta');
        Route::get('/showMap', 'showMap')->name('pemetaan');
        Route::get('/geojson/{tahun}', 'geojsonData')->name('geojsonData');
    });

    Route::controller(adminController::class)->prefix('user')->middleware('superadmin')->group(function() {
        Route::get('/', 'index')->name('user');
        Route::get('allUser', 'allUser')->name('allUser');
        Route::get('tambah', 'tambah')->name('user.tambah');
        Route::post('tambah', 'simpan')->name('user.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('user.edit');
        Route::post('edit/{id}', 'update')->name('user.tambah.update');
        Route::delete('hapus/{id}', 'hapus')->name('user.hapus');
    });

});

