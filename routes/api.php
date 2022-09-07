<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Reservasi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('karyawan', 'Api\AuthController@index');
    Route::get('karyawan/{id}', 'Api\AuthController@show');
    Route::post('karyawan', 'Api\AuthController@store');
    Route::put('karyawan/{id}', 'Api\AuthController@update');
    Route::put('nonaktif/{id}', 'Api\AuthController@deactivateAcc');
    Route::delete('karyawan/{id}', 'Api\AuthController@destroy');

    // Meja
    Route::get('meja', 'Api\MejaController@index');
    Route::get('meja/{tanggal}/{sesi}', 'Api\MejaController@index');
    Route::post('meja', 'Api\MejaController@store');
    Route::put('meja/{id}', 'Api\MejaController@update');
    Route::delete('meja/{id}', 'Api\MejaController@destroy');

    Route::get('getDetailMeja/{id}', 'Api\MejaController@getDetailMeja');
    Route::post('statusMeja', 'Api\MejaController@statusMeja');

    // Bahan
    Route::get('Bahan', 'Api\BahanController@index');
    Route::get('Bahan/{id}', 'Api\BahanController@show');
    Route::post('Bahan', 'Api\BahanController@store');
    Route::put('Bahan/{id}', 'Api\BahanController@update');
    Route::delete('Bahan/{id}', 'Api\BahanController@destroy');

    // Incoming stok
    Route::get('incoming', 'Api\IncomingController@index');
    Route::get('incoming/{id}', 'Api\IncomingController@show');
    Route::post('incoming', 'Api\IncomingController@store');
    Route::put('incoming/{id}', 'Api\IncomingController@update');
    Route::delete('incoming/{id}', 'Api\IncomingController@destroy');

    // Waste stok
    Route::get('waste', 'Api\WasteController@index');
    Route::get('waste/{id}', 'Api\WasteController@show');
    Route::post('waste', 'Api\WasteController@store');
    Route::put('waste/{id}', 'Api\WasteController@update');
    Route::delete('waste/{id}', 'Api\WasteController@destroy');

    // Menu

    Route::post('Menu', 'Api\MenuController@store');
    Route::post('Menu/image/', 'Api\MenuController@updateFoto');
    Route::put('Menu/{id}', 'Api\MenuController@update');
    Route::delete('Menu/{id}', 'Api\MenuController@destroy');

    // Customer
    Route::get('Customer', 'Api\CustomerController@index');
    Route::get('Customer/{id}', 'Api\CustomerController@show');
    Route::post('Customer', 'Api\CustomerController@store');
    Route::put('Customer/{id}', 'Api\CustomerController@update');
    Route::delete('Customer/{id}', 'Api\CustomerController@destroy');

    // Reservasi
    Route::get('Reservasi', 'Api\ReservasiController@index');
    Route::get('ReservasiShow/{id}', 'Api\ReservasiController@show');
    Route::post('Reservasi', 'Api\ReservasiController@store');
    Route::put('Reservasi/{id}', 'Api\ReservasiController@update');
    Route::delete('Reservasi/{id}', 'Api\ReservasiController@cancelReservasi');

    // Order
    Route::get('Order', 'Api\OrderController@index');
    Route::get('Order/queue', 'Api\OrderController@QueueOrder');
    Route::get('Order/ready', 'Api\OrderController@ReadyOrder');
    Route::put('Order', 'Api\OrderController@updateStatus');
    Route::get('Order/detail/{id}', 'Api\OrderController@OrderDetail');
    Route::get('Order/waiter/{id}', 'Api\OrderController@getWaiter');
    Route::get('Order/cashier/{id}', 'Api\OrderController@getCashier');
    Route::get('Order/card', 'Api\OrderController@getCard');

    // QRCODE
    Route::get('qrcode/{id}', function ($id) {
        $reservasi = Reservasi::find($id);
        $data = new stdClass;
        $data->id = $id;
        $data->tanggal_reservasi = $reservasi->tanggal_reservasi;
        $data->sesi = $reservasi->sesi;
        $string = json_encode($data);
        QrCode::size(200)
            ->format('svg')
            ->generate($string, public_path() . '/qrcode' . $id . '.svg');
    });

    // Cetak
    Route::get('Reservasi/{id}/{id_user}', 'Api\ReservasiController@cetak');

    // Laporan
    Route::get('pendapatan-bulanan/{tahun}', 'Api\LaporanController@pendapatan_bulanan');
    Route::get('pendapatan-tahunan/{from}/{to}', 'Api\LaporanController@pendapatan_tahunan');
    Route::get('pengeluaran-bulanan/{tahun}', 'Api\LaporanController@pengeluaran_bulanan');
    Route::get('pengeluaran-tahunan/{from}/{to}', 'Api\LaporanController@pengeluaran_tahunan');
    Route::get('stok-bulanan/{tanggal}/{id_bahan}', 'Api\LaporanController@stok_bulanan');
    Route::get('stok-custom/{from}/{to}', 'Api\LaporanController@stok_custom');
    Route::get('penjualan-item/{tahun}/{bulan}', 'Api\LaporanController@penjualan_item');

    Route::put('payment-cash/{id}', 'Api\OrderController@cash');
    Route::put('payment-debit/{id}', 'Api\OrderController@debit');
    Route::put('payment-kredit/{id}', 'Api\OrderController@kredit');

    // Logout
    Route::post('logout', 'Api\AuthController@logout');
});

// Route::get('penjualan-item/{tahun}/{bulan}', 'Api\LaporanController@penjualan_item');

Route::get('stok-bulanan', 'Api\LaporanController@stok_bulanan');

Route::get('Menu', 'Api\MenuController@index');
Route::get('Menu/{id}', 'Api\MenuController@kategoriMenu');
Route::get('Menushow/{id}', 'Api\MenuController@show');

Route::get('Order/byReservasi/{id}', 'Api\OrderController@show');
Route::post('Order', 'Api\OrderController@store');
// Detail Order
Route::post('DetailOrderAPI/create', 'Api\OrderController@detailCreate');
Route::get('DetailOrder/get/{id}', 'Api\OrderController@getDetail');
Route::get('EndPesanan/{id}', 'Api\OrderController@end');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
