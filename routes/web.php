<?php

use Illuminate\Support\Facades\Route;
use App\User;
use App\Bahan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// QRCODE
// Route::get('qrcode/{id}', function ($id) {
//     $reservasi = Reservasi::find($id);
//     $data = new stdClass;
//     $data->id = $id;
//     $data->tanggal_reservasi = $reservasi->tanggal_reservasi;
//     $data->sesi = $reservasi->sesi;
//     $string = json_encode($data);
//     QrCode::size(200)
//         ->format('svg')
//         ->generate($string, public_path() . '/qrcode' . $id . '.svg');
// });

Route::get('/', function () {
    return view('welcome');
});
Route::get('cetak/{id}/{id_user}', function ($id, $id_user) {
    Log::info($id);
    $user = User::find($id_user);
    return view('cetak', ["id" => $id, "user" => $user]);
});
Route::get('pendapatan-bulanan/{tahun}/{id_user}', function ($tahun, $id_user) {
    $result = DB::select(
        "SELECT  m.month AS Bulan,
                COALESCE(SUM(IF(menus.kategori = 'MainCourse', (detail_orders.subtotal), 0)),0) AS Makanan,
                COALESCE(SUM(IF(menus.kategori = 'SideDish', (detail_orders.subtotal), 0)),0) AS SideDish,
                COALESCE(SUM(IF(menus.kategori = 'Drink', (detail_orders.subtotal), 0)),0) AS Minuman,
                COALESCE(SUM(detail_orders.subtotal), 0) AS TotalPendapatan
            FROM detail_orders
            JOIN menus ON menus.id = detail_orders.id_menu
            RIGHT JOIN (
                SELECT 'January' AS month UNION
                SELECT 'February' AS month UNION
                SELECT 'March' AS month UNION
                SELECT 'April' AS month UNION
                SELECT 'May' AS month UNION
                SELECT 'June' AS month UNION
                SELECT 'July' AS month UNION
                SELECT 'August' AS month UNION
                SELECT 'September' AS month UNION
                SELECT 'October' AS month UNION
                SELECT 'November' AS month UNION
                SELECT 'Desember' AS month 
            ) AS m 
            ON year(detail_orders.created_at) = {$tahun}
            AND MONTHNAME(detail_orders.created_at) = m.month
            GROUP BY m.month
            ORDER BY 1+1;
        "
    );
    $user = User::find($id_user);
    return view('pendapatan', [
        "result" => $result,
        "user" => $user,
        "tahun" => $tahun,
        "tipe" => "BULANAN"
    ]);
});
Route::get('pendapatan-tahunan/{from}/{to}/{id_user}', function ($from, $to, $id_user) {
    $result = DB::select(
        "SELECT  seq AS Tahun,
                COALESCE(SUM(IF(menus.kategori = 'MainCourse', (detail_orders.subtotal), 0)),0) AS Makanan,
                COALESCE(SUM(IF(menus.kategori = 'SideDish', (detail_orders.subtotal), 0)),0) AS SideDish,
                COALESCE(SUM(IF(menus.kategori = 'Drink', (detail_orders.subtotal), 0)),0) AS Minuman,
                COALESCE(SUM(detail_orders.subtotal), 0) AS TotalPendapatan
            FROM detail_orders 
            JOIN menus ON menus.id = detail_orders.id_menu
            RIGHT JOIN seq_{$from}_to_{$to} ON seq_{$from}_to_{$to}.seq = year(detail_orders.created_at)
            GROUP BY seq_{$from}_to_{$to}.seq;
        "
    );
    $user = User::find($id_user);
    return view(
        'pendapatan',
        [
            "result" => $result,
            "user" => $user,
            "from" => $from,
            "to" => $to,
            "tipe" => "TAHUNAN"
        ]
    );
});
Route::get('pengeluaran-bulanan/{tahun}/{id_user}',  function ($tahun, $id_user) {
    $result = DB::select("SELECT  m.month AS Bulan,
            COALESCE(SUM(IF(menus.kategori = 'MainCourse', (incoming_stocks.harga), 0)),0) AS Makanan,
            COALESCE(SUM(IF(menus.kategori = 'SideDish', (incoming_stocks.harga), 0)),0) AS SideDish,
            COALESCE(SUM(IF(menus.kategori = 'Drink', (incoming_stocks.harga), 0)),0) AS Minuman,
            COALESCE(SUM(incoming_stocks.harga), 0) AS TotalPengeluaran
        FROM incoming_stocks
        JOIN bahans ON bahans.id = incoming_stocks.id_bahan
        JOIN menus ON menus.id = bahans.id_menu
        RIGHT JOIN (
            SELECT 'January' AS month UNION
            SELECT 'February' AS month UNION
            SELECT 'March' AS month UNION
            SELECT 'April' AS month UNION
            SELECT 'May' AS month UNION
            SELECT 'June' AS month UNION
            SELECT 'July' AS month UNION
            SELECT 'August' AS month UNION
            SELECT 'September' AS month UNION
            SELECT 'October' AS month UNION
            SELECT 'November' AS month UNION
            SELECT 'Desember' AS month 
        ) AS m 
        ON year(incoming_stocks.created_at) = $tahun
        AND MONTHNAME(incoming_stocks.created_at) = m.month
        GROUP BY m.month
        ORDER BY 1+1;");
    $user = User::find($id_user);
    return view('pengeluaran', ["result" => $result, "user" => $user, "tahun" => $tahun, "tipe" => "BULANAN"]);
});
Route::get('pengeluaran-tahunan/{from}/{to}/{id_user}', function ($from, $to, $id_user) {
    $result = DB::select("SELECT  seq AS Tahun,
            COALESCE(SUM(IF(menus.kategori = 'MainCourse', (incoming_stocks.harga), 0)),0) AS Makanan,
            COALESCE(SUM(IF(menus.kategori = 'SideDish', (incoming_stocks.harga), 0)),0) AS SideDish,
            COALESCE(SUM(IF(menus.kategori = 'Drink', (incoming_stocks.harga), 0)),0) AS Minuman,
            COALESCE(SUM(incoming_stocks.harga), 0) AS TotalPengeluaran
        FROM incoming_stocks 
        JOIN bahans ON bahans.id = incoming_stocks.id_bahan
        JOIN menus ON menus.id = bahans.id_menu
        RIGHT JOIN seq_{$from}_to_{$to} ON seq_{$from}_to_{$to}.seq = year(incoming_stocks.created_at)
        GROUP BY seq_{$from}_to_{$to}.seq;
        ");

    $user = User::find($id_user);
    return view(
        'pengeluaran',
        [
            "result" => $result,
            "user" => $user,
            "from" => $from,
            "to" => $to,
            "tipe" => "TAHUNAN"
        ]
    );
});
Route::get('penjualan-item/{tahun}/{bulan}/{id_user}', function ($tahun, $bulan, $id_user) {
    if ($bulan != "ALL") {
        $result = DB::select("SELECT menus.kategori, menus.nama_menu AS nama_menu, menus.unit as unit,
                COALESCE(MAX(A.kuantitas),0) AS penjualan_harian_tertinggi,
                COALESCE(SUM(A.kuantitas),0) AS total_penjualan
            FROM menus 
            LEFT JOIN (
                SELECT *
                FROM detail_orders
                WHERE MONTH(detail_orders.created_at) = {$bulan}
                AND YEAR(detail_orders.created_at) = {$tahun}
                OR kuantitas IS NULL
            ) as A  ON A.id_menu = menus.id
            GROUP BY menus.nama_menu
            ORDER BY  menus.nama_menu ASC");
        $bulan_string = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];
        $bulan = $bulan_string[(int)$bulan - 1];
    } else {
        $result = DB::select("SELECT menus.kategori, menus.nama_menu AS nama_menu, menus.unit as unit,
                COALESCE(MAX(A.kuantitas),0) AS penjualan_harian_tertinggi,
                COALESCE(SUM(A.kuantitas),0) AS total_penjualan
            FROM menus 
            LEFT JOIN (
                SELECT *
                FROM detail_orders
                WHERE YEAR(detail_orders.created_at) = {$tahun}
                OR kuantitas IS NULL
            ) as A  ON A.id_menu = menus.id
            GROUP BY menus.nama_menu
            ORDER BY menus.nama_menu ASC");
    }

    $user = User::find($id_user);
    return view(
        'penjualan',
        [
            "result" => $result,
            "user" => $user,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "tipe" => "TAHUNAN"
        ]
    );
});
Route::get('stok-bulanan/{tanggal}/{id_bahan}/{id_user}', function ($tanggal = null, $id_bahan, $id_user) {

    if ($tanggal) {
        $firstDay = $tanggal;
    }
    $bahan = Bahan::find($id_bahan);
    $result =
        DB::select("SELECT 
                            `DetailList`.`Date`, 
                            `HistoryList`.`Incoming`, 
                            (`HistoryList`.`Incoming` - `DetailList`.`Detail` - `HistoryList`.`Waste`) AS `Remaining`,
                            `HistoryList`.`Waste`
                        FROM (
                            SELECT `dateList`.`Date` ,
                            CASE WHEN `do`.`created_at` IS NULL THEN 0
                            ELSE COALESCE(SUM(IF(`do`.`id_menu` = `menuBahan`.`id_menu`,`do`.`kuantitas`,0)), 0) 
                            END AS `Detail`
                            FROM
                            (
                                SELECT `a`.`Date`
                                FROM (
                                    SELECT LAST_DAY('{$firstDay}') - INTERVAL (`a`.`a` + (10 * `b`.`a`) + (100 * `c`.`a`)) DAY AS `Date`
                                    FROM (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `a`
                                    CROSS JOIN (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `b`
                                    CROSS JOIN (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `c`
                                    ) AS `a` 
                                WHERE `a`.`Date` between '{$firstDay}' and LAST_DAY('{$firstDay}')
                            ) AS `dateList` 
                        
                            LEFT JOIN detail_orders AS `do` ON `dateList`.`Date` = DATE(`do`.`created_at`)
                            LEFT JOIN (
                                SELECT `bahans`.`id` AS `id_bahan`, `menus`.`id` AS `id_menu`
                                FROM `bahans`
                                JOIN `menus` ON `bahans`.`id_menu` = `menus`.`id`
                                WHERE `bahans`.`id` = {$id_bahan}
                            ) AS `menuBahan` ON `do`.`id_menu` = `menuBahan`.`id_menu`
                            GROUP BY `dateList`.`Date`
                            ORDER BY `dateList`.`Date` ASC
                        ) AS `DetailList` 
                        JOIN (
                            SELECT `dateList`.`Date`,
                            CASE WHEN `in`.`created_at` IS NULL THEN 0
                            ELSE COALESCE(SUM(IF(`in`.`id_bahan` = {$id_bahan},(`in`.`jumlah`),0)), 0) 
                            END AS `Incoming`,
                        
                            CASE WHEN `ws`.`created_at` IS NULL THEN 0
                            ELSE COALESCE(SUM(IF(`ws`.`id_bahan` = {$id_bahan},(`ws`.`jumlah`),0)), 0) 
                            END AS `Waste`
                            FROM
                            (
                                SELECT `a`.`Date`
                                FROM (
                                    SELECT LAST_DAY('{$firstDay}') - INTERVAL (`a`.`a` + (10 * `b`.`a`) + (100 * `c`.`a`)) DAY AS `Date`
                                    FROM (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `a`
                                    CROSS JOIN (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `b`
                                    CROSS JOIN (
                                        SELECT 0 AS `a` UNION ALL 
                                        SELECT 1 UNION ALL 
                                        SELECT 2 UNION ALL 
                                        SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL 
                                        SELECT 5 UNION ALL 
                                        SELECT 6 UNION ALL 
                                        SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL 
                                        SELECT 9) AS `c`
                                    ) AS `a` 
                                    
                                    WHERE `a`.`Date` between '{$firstDay}' and LAST_DAY('{$firstDay}')
                                ) AS `dateList` 
                        
                                LEFT JOIN incoming_stocks AS `in` ON `dateList`.`Date` = DATE(`in`.`created_at`)
                                LEFT JOIN waste_stocks AS `ws` ON `dateList`.`Date` = DATE(`ws`.`created_at`)
                                GROUP BY `dateList`.`Date`
                                ORDER BY `dateList`.`Date` ASC
                        ) `HistoryList` ON  `DetailList`.`Date` = `HistoryList`.`Date`
                    
                    ");
    $bahan = Bahan::find($id_bahan);
    $user = User::find($id_user);
    return view(
        'stok_bulanan',
        [
            "result" => $result,
            "bahan" => $bahan,
            "tanggal" => $tanggal,
            "user" => $user,
        ]
    );
    // $response = response([
    //     'message' => 'Retrieve Data Success',
    //     'data' => $result,
    //     'tahun' => substr($firstDay, 0, 4),
    //     'bahan' => $bahan,
    // ], 200);
    // Log::info($response);
    // return $response;
});
Route::get('stok-custom/{from}/{to}/{user}', function ($from, $to, $user) {

    $bahan = DB::table('bahans')
        ->leftJoin('menus', 'bahans.id_menu', '=', 'menus.id')
        ->where('bahans.deleted_at', '=', NULL)
        ->orderBy('bahans.id', 'ASC')
        ->get();

    $incoming = DB::select("SELECT bahans.id, bahans.nama_bahan , COALESCE(SUM(inc.jumlah),0) as jumlah FROM bahans
            LEFT JOIN (
                SELECT * 
                FROM incoming_stocks
                WHERE DATE(incoming_stocks.created_at) BETWEEN '{$from}' AND '{$to}'
            ) as inc ON inc.id_bahan = bahans.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan
            ORDER BY bahans.id ASC ");

    $waste = DB::select("SELECT bahans.id, COALESCE(SUM(inc.jumlah),0) as jumlah FROM bahans
            LEFT JOIN (
                SELECT * 
                FROM waste_stocks
                WHERE DATE(waste_stocks.created_at) BETWEEN '{$from}' AND '{$to}'
            ) as inc ON inc.id_bahan = bahans.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan
            ORDER BY bahans.id ASC ");

    $penjualan = DB::select("SELECT COALESCE(SUM(detail_orders.kuantitas),0) * bahans.serving_size AS jumlah FROM bahans
            LEFT JOIN menus ON menus.id = bahans.id_menu
            LEFT JOIN (
                SELECT * 
                FROM detail_orders
                WHERE DATE(detail_orders.created_at) BETWEEN '{$from}' AND '{$to}'
            ) as detail_orders ON detail_orders.id_menu = menus.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan
            ORDER BY bahans.id ASC");
    $user = User::find($user);
    return view(
        'stok_custom',
        [
            'bahan' => $bahan,
            'incoming' => $incoming,
            'penjualan' =>  $penjualan,
            'waste' => $waste,
            'user' => $user,
            'start' => $from,
            'end' => $to
        ]
    );
});



Route::get('cetak-struk/{id}/{id_user}', function ($id, $id_user) {
    $order = DB::table('detail_orders')
        ->join('menus', 'menus.id', '=', 'detail_orders.id_menu')
        ->selectRaw(
            "
                detail_orders.id,
                menus.nama_menu,
                menus.harga as harga,
                sum(detail_orders.kuantitas) as kuantitas,
                sum(detail_orders.subtotal) as subtotal
                "
        )
        ->groupBy('menus.nama_menu')
        ->where('detail_orders.id_order', '=', $id)
        ->get();
    $user = User::find($id_user);
    $reservasi = DB::table('orders')
        ->join('reservasis', 'reservasis.id', '=', 'orders.id_reservasi')
        ->join('users', 'reservasis.id_karyawan', '=', 'users.id')
        ->where('orders.id', '=', $id)
        ->first();
    $meja = DB::table('reservasis')
        ->join('mejas', 'mejas.id', '=', 'reservasis.id_meja')
        ->join('customers', 'customers.id', '=', 'reservasis.id_customer')
        ->where('reservasis.id', '=', $reservasi->id_reservasi)
        ->first();
    return view(
        'struk',
        [
            "detail" => $order,
            "user" => $user,
            "reservasi" => $reservasi,
            "meja" => $meja,
        ]
    );
});