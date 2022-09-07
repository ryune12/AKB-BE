INSERT INTO `orders` 
    (`id`, `id_reservasi`, `id_karyawan`, `total`, `total_qty`, `total_item`, `tax`, `services`, `jenis_pembayaran`, `id_kartu`, `kode_verifikasi`, `status_pembayaran`, `created_at`, `updated_at`, `deleted_at`) 
VALUES 
    (NULL, '6', '18', '20000', '1', '1', '2000', '1000', 'debit', '2720707123189784', '123456', 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '7', '18', '240000', '15', '2', '24000', '12000', 'debit', '2720707123189784', '123456', 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '8', '18', '40000', '2', '1', '4000', '2000', 'debit', '2720707123189784', '123456', 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '9', '18', '170000', '10', '2', '17000', '8500', 'cash', '2720707123189784', NULL, 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '10', '18', '145000', '21', '4', '14500', '7250', 'cash', '2720707123189784', NULL, 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '11', '18', '8000', '1', '1', '800', '400', 'cash', '2720707123189784', NULL, 'Lunas', CURRENT_TIME(), NULL, NULL), 
    (NULL, '12', '18', '61000', '10', '3', '6100', '3050', 'cash', '4024007145187745', NULL, 'Lunas', CURRENT_TIME(), NULL, NULL);


INSERT INTO `detail_orders`(`id`, `id_order`, `id_menu`, `kuantitas`, `subtotal`, `created_at`, `updated_at`, `deleted_at`)
VALUES 
    (NULL, '1', '1', '5','100000', CURRENT_TIME(), NULL, NULL),
    (NULL, '2', '1', '1','20000', CURRENT_TIME(), NULL, NULL),
    (NULL, '2', '2', '1','15000', CURRENT_TIME(), NULL, NULL),
    (NULL, '2', '10', '2','16000', CURRENT_TIME(), NULL, NULL),
    (NULL, '3', '2', '2','30000', CURRENT_TIME(), NULL, NULL),
    (NULL, '3', '6', '16','80000', CURRENT_TIME(), NULL, NULL),
    (NULL, '4', '5', '4','16000', CURRENT_TIME(), NULL, NULL),
    (NULL, '4', '6', '5','25000', CURRENT_TIME(), NULL, NULL),
    (NULL, '4', '9', '2','12000', CURRENT_TIME(), NULL, NULL),
    (NULL, '4', '4', '3','66000', CURRENT_TIME(), NULL, NULL),
    (NULL, '5', '10', '6','48000', CURRENT_TIME(), NULL, NULL),
    (NULL, '13', '1', '1','20000', CURRENT_TIME(), NULL, NULL),
    (NULL, '14', '4', '10','220000', CURRENT_TIME(), NULL, NULL),
    (NULL, '14', '5', '5','20000', CURRENT_TIME(), NULL, NULL),
    (NULL, '15', '1', '2','40000', CURRENT_TIME(), NULL, NULL),
    (NULL, '16', '2', '6','90000', CURRENT_TIME(), NULL, NULL),
    (NULL, '16', '3', '4','80000', CURRENT_TIME(), NULL, NULL)


/*
    Pengeluaran Bulanan
*/

SELECT  m.month AS Bulan,
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
ON year(incoming_stocks.created_at) = 2021
AND MONTHNAME(incoming_stocks.created_at) = m.month
GROUP BY m.month
ORDER BY 1+1;

/*
    Pengeluaran Tahunan
*/
SELECT  seq AS Tahun,
    COALESCE(SUM(IF(menus.kategori = 'MainCourse', (incoming_stocks.harga), 0)),0) AS Makanan,
    COALESCE(SUM(IF(menus.kategori = 'SideDish', (incoming_stocks.harga), 0)),0) AS SideDish,
    COALESCE(SUM(IF(menus.kategori = 'Drink', (incoming_stocks.harga), 0)),0) AS Minuman,
    COALESCE(SUM(incoming_stocks.harga), 0) AS TotalPengeluaran
FROM incoming_stocks 
JOIN bahans ON bahans.id = incoming_stocks.id_bahan
JOIN menus ON menus.id = bahans.id_menu
RIGHT JOIN seq_2019_to_2021 ON seq_2019_to_2021.seq = year(incoming_stocks.created_at)
GROUP BY seq_2019_to_2021.seq;


/*
    Pendapatan Bulanan
*/
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
ON year(detail_orders.created_at) = 2021
AND MONTHNAME(detail_orders.created_at) = m.month
GROUP BY m.month
ORDER BY 1+1;

/*
    Pendapatan Tahunan
*/
SELECT  seq AS Tahun,
    COALESCE(SUM(IF(menus.kategori = 'MainCourse', (detail_orders.subtotal), 0)),0) AS Makanan,
    COALESCE(SUM(IF(menus.kategori = 'SideDish', (detail_orders.subtotal), 0)),0) AS SideDish,
    COALESCE(SUM(IF(menus.kategori = 'Drink', (detail_orders.subtotal), 0)),0) AS Minuman,
    COALESCE(SUM(detail_orders.subtotal), 0) AS TotalPengeluaran
FROM detail_orders 
JOIN menus ON menus.id = detail_orders.id_menu
RIGHT JOIN seq_2019_to_2021 ON seq_2019_to_2021.seq = year(detail_orders.created_at)
GROUP BY seq_2019_to_2021.seq;

/*
    Penjualan Item Menu 
*/

SELECT 	menus.kategori, menus.nama_menu AS nama_menu, menus.unit as unit,
	COALESCE(MAX(detail_orders.kuantitas),0) AS DailyMAXSales,
	COALESCE(SUM(detail_orders.kuantitas),0) AS TotalPenjualan
FROM menus 
LEFT JOIN (
	SELECT *
	FROM detail_orders
	WHERE MONTH(detail_orders.created_at) = 3
	AND YEAR(detail_orders.created_at) = 2021
) as A  ON A.id_menu = menus.id
WHERE kuantitas IS NULL
GROUP BY menus.nama_menu
ORDER BY menus.kategori

SELECT nama_bahan, jumlah 
FROM bahans
WHERE jumlah < serving_size


/*
    Laporan Stok Custom
*/
SELECT COALESCE(SUM(detail_orders.kuantitas),0) * bahans.serving_size AS TotalPenjualan FROM bahans
LEFT JOIN menus ON menus.id = bahans.id_menu
LEFT JOIN (
    SELECT * 
    FROM detail_orders
    WHERE DATE(detail_orders.created_at) BETWEEN '2021-03-16' AND '2021-03-18'
) as detail_orders ON detail_orders.id_menu = menus.id
GROUP BY bahans.nama_bahan



/*
    Laporan Stok Selama satu bulan
*/
SELECT 
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
            SELECT LAST_DAY('2021-03-01') - INTERVAL (`a`.`a` + (10 * `b`.`a`) + (100 * `c`.`a`)) DAY AS `Date`
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
        WHERE `a`.`Date` between '2021-03-01' and LAST_DAY('2021-03-01')
    ) AS `dateList` 

    LEFT JOIN detail_order AS `do` ON `dateList`.`Date` = DATE(`do`.`created_at`)
    LEFT JOIN (
        SELECT `bahans`.`id` AS `id_bahan`, `menus`.`id` AS `id_menu`
        FROM `bahans`
        JOIN `menus` ON `bahans`.`id_menu` = `menus`.`id`
        WHERE `bahans`.`id` = 1
    ) AS `menuBahan` ON `do`.`id_menu` = `menuBahan`.`id_menu`
    GROUP BY `dateList`.`Date`
    ORDER BY `dateList`.`Date` ASC
) AS `DetailList` 
JOIN (
    SELECT `dateList`.`Date`,
    CASE WHEN `in`.`created_at` IS NULL THEN 0
    ELSE COALESCE(SUM(IF(`in`.`id_bahan` = 1,(`in`.`jumlah`),0)), 0) 
    END AS `Incoming`,

    CASE WHEN `ws`.`created_at` IS NULL THEN 0
    ELSE COALESCE(SUM(IF(`ws`.`id_bahan` = 1,(`ws`.`jumlah`),0)), 0) 
    END AS `Waste`
    FROM
    (
        SELECT `a`.`Date`
        FROM (
            SELECT LAST_DAY('2021-03-01') - INTERVAL (`a`.`a` + (10 * `b`.`a`) + (100 * `c`.`a`)) DAY AS `Date`
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
            
            WHERE `a`.`Date` between '2021-03-01' and LAST_DAY('2021-03-01')
        ) AS `dateList` 

        LEFT JOIN incoming_stocks AS `in` ON `dateList`.`Date` = DATE(`in`.`created_at`)
        LEFT JOIN waste_stocks AS `ws` ON `dateList`.`Date` = DATE(`ws`.`created_at`)
        GROUP BY `dateList`.`Date`
        ORDER BY `dateList`.`Date` ASC
) `HistoryList` ON  `DetailList`.`Date` = `HistoryList`.`Date`






