<?php

use App\Http\Controllers\sampahController;
use App\Http\Controllers\jarakController;
use App\Http\Controllers\kecamatanController;
use App\Http\Controllers\kelurahanController;
use App\Http\Controllers\parameterController;
use App\Http\Controllers\prosesClustering;
use App\Http\Controllers\prosesController;
use App\Http\Controllers\tpsController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('dashboard');
});

Route::controller(kecamatanController::class)->prefix('kecamatan')->group(function () {
    Route::get('/', 'index')->name('kecamatan');
    Route::get('tambah', 'tambah')->name('kecamatan.tambah');
    Route::post('tambah', 'simpan')->name('kecamatan.tambah.simpan');
    Route::get('edit/{id}', 'edit')->name('kecamatan.edit');
    Route::post('edit/{id}', 'update')->name('kecamatan.tambah.update');
    Route::delete('id/{id}', 'hapus')->name('kecamatan.hapus');
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
});

Route::controller(sampahController::class)->prefix('sampah')->group(function() {
    Route::get('/', 'index')->name('sampah');
    Route::get('tambah', 'tambah')->name('sampah.tambah');
    Route::post('tambah', 'simpan')->name('sampah.tambah.simpan');
    Route::get('edit/{id}', 'edit')->name('sampah.edit');
    Route::post('edit/{id}', 'update')->name('sampah.tambah.update');
    Route::delete('id/{id}', 'hapus')->name('sampah.hapus');
    Route::get('import', 'importForm')->name('sampah.import');
    Route::post('import', 'import')->name('sampah.import.simpan');
});

Route::controller(parameterController::class)->prefix('parameter')->group(function() {
    Route::get('/', 'index')->name('parameter');
    Route::get('tambah', 'tambah')->name('parameter.tambah');
    Route::post('tambah', 'simpan')->name('parameter.tambah.simpan');
    Route::get('edit/{id}', 'edit')->name('parameter.edit');
    Route::post('edit/{id}', 'update')->name('parameter.tambah.update');
    Route::delete('id/{id}', 'hapus')->name('parameter.hapus');
});

Route::controller(userController::class)->prefix('user')->group(function() {
    Route::get('/', 'index')->name('user');
});

Route::controller(prosesController::class)->prefix('proses')->group(function() {
    Route::get('/proses', 'showProses')->name('proses');
    Route::post('/proses', 'showProses')->name('proses.cluster');
});

// Route::get('/normalize', [prosesController::class, 'normalizeSampahData']);

// Route::get('/performClustering', [prosesController::class, 'performClustering']);


