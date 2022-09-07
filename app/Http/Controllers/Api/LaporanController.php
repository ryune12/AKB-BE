<?php

namespace App\Http\Controllers\Api;

use App\Bahan;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function pendapatan_bulanan($tahun = null)
    {
        if ($tahun == null) {
            $tahun = Carbon::now()->format('Y');
        }
        $result = DB::select("
            SELECT  m.month AS Bulan,
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
        ");
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            "tahun" => $tahun
        ], 200);
        Log::info($response);
        return $response;
    }
    public function pendapatan_tahunan($from = null, $to = null)
    {
        $result = DB::select("SELECT  seq AS Tahun,
                COALESCE(SUM(IF(menus.kategori = 'MainCourse', (detail_orders.subtotal), 0)),0) AS Makanan,
                COALESCE(SUM(IF(menus.kategori = 'SideDish', (detail_orders.subtotal), 0)),0) AS SideDish,
                COALESCE(SUM(IF(menus.kategori = 'Drink', (detail_orders.subtotal), 0)),0) AS Minuman,
                COALESCE(SUM(detail_orders.subtotal), 0) AS TotalPendapatan
            FROM detail_orders 
            JOIN menus ON menus.id = detail_orders.id_menu
            RIGHT JOIN seq_{$from}_to_{$to} ON seq_{$from}_to_{$to}.seq = year(detail_orders.created_at)
            GROUP BY seq_{$from}_to_{$to}.seq;
        ");
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            'from' => $from,
            'to' => $to
        ], 200);
        Log::info($response);
        return $response;
    }
    public function pengeluaran_bulanan($tahun = null)
    {
        $result =
            DB::select("SELECT  m.month AS Bulan,
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
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            "tahun" => $tahun
        ], 200);
        Log::info($response);
        return $response;
    }
    public function pengeluaran_tahunan($from = null, $to = null)
    {
        $result =
            DB::select("SELECT  seq AS Tahun,
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
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            'from' => $from,
            'to' => $to
        ], 200);
        Log::info($response);
        return $response;
    }
    public function penjualan_item($tahun = null, $bulan = null)
    {
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
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ], 200);
        Log::info($response);
        return $response;
    }
    public function stok_bulanan($tanggal = null, $id_bahan)
    {
        Log::info($tanggal);
        Log::info($id_bahan);
        // Log::info(urldecode($tanggal));


        if ($tanggal) {
            $firstDay = $tanggal;
        }
        // Log::info($firstDay);
        // Log::info($firstDay);
        // $firstDay = date('Y-m-01');
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
                            ELSE COALESCE(SUM(IF(`do`.`id_menu` = `menuBahan`.`id_menu`,`do`.`kuantitas`  * menuBahan.serving_size,0)), 0)
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
                                SELECT `bahans`.`id` AS `id_bahan`, `menus`.`id` AS `id_menu`, `bahans`.`serving_size` as serving_size
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
        $response = response([
            'message' => 'Retrieve Data Success',
            'data' => $result,
            'tahun' => substr($firstDay, 0, 7),
            'bahan' => $bahan,
        ], 200);
        return $response;
    }
    public function stok_custom($start = null, $end = null)
    {
        if ($start == null)
            $start = '2021-03-16';
        if ($end == null)
            $end = '2021-03-18';
        $bahan = DB::table('bahans')
            ->leftJoin('menus', 'bahans.id_menu', '=', 'menus.id')
            ->where('bahans.deleted_at', '=', NULL)
            ->get();

        $incoming = DB::select("SELECT bahans.id, COALESCE(SUM(inc.jumlah),0) as jumlah FROM bahans
            LEFT JOIN (
                SELECT * 
                FROM incoming_stocks
                WHERE DATE(incoming_stocks.created_at) BETWEEN '{$start}' AND '{$end}'
            ) as inc ON inc.id_bahan = bahans.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan
            ORDER BY bahans.id ASC");

        $waste = DB::select("SELECT bahans.id, COALESCE(SUM(inc.jumlah),0) as jumlah FROM bahans
            LEFT JOIN (
                SELECT * 
                FROM waste_stocks
                WHERE DATE(waste_stocks.created_at) BETWEEN '{$start}' AND '{$end}'
            ) as inc ON inc.id_bahan = bahans.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan 
            ORDER BY bahans.id ASC");

        $penjualan = DB::select("SELECT COALESCE(SUM(detail_orders.kuantitas),0) * bahans.serving_size AS jumlah FROM bahans
            LEFT JOIN menus ON menus.id = bahans.id_menu
            LEFT JOIN (
                SELECT * 
                FROM detail_orders
                WHERE DATE(detail_orders.created_at) BETWEEN '{$start}' AND '{$end}'
            ) as detail_orders ON detail_orders.id_menu = menus.id
            WHERE bahans.deleted_at IS NULL
            GROUP BY bahans.nama_bahan
            ORDER BY bahans.id ASC");
        $response = response([
            'message' => 'Retrieve Data Success',
            'bahan' => $bahan,
            'waste' => $waste,
            'penjualan' => $penjualan,
            'incoming' => $incoming,
            'start' => $start,
            'end' => $end,
        ], 200);
        return $response;
    }
}