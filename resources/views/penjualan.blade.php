<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table width="90%" style="margin: auto;" class="report-container">
        <thead class="report-header">
            <tr>
                <th class="report-header-cell">
                    <div class="logo" style="margin-top: 50px; text-align: justify;">
                        <img src="{{ asset('logo.png') }}" alt="">
                        <div style="margin-left: 40px;">
                            <h1>ATMA KOREAN BBQ</h1>
                            <p style="color: #C00000; margin-top: -20px; text-align: center;">FUN PLACE TO GRILL!</p>
                            <p style="text-align: center; margin-top: -20px; font-family: Calibri, sans-serif;">Jl.
                                Babarsari No. 43 Yogyakarta 552181
                                <br>
                                Telp. (0274) 487711 | http://www.atmakoreanbbq.com
                            </p>
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
        <tfoot class="report-footer">
            <tr>
                <td class="report-footer-cell">
                    <div class="footer"
                        style="text-align: center; margin-bottom: 50px; margin-top: 20px; bottom: 0px; ">

                        <span>Printed <?= @date('M d, Y H:i:s A') ?></span><br>
                        <span>Printed by <?= $user->nama ?></span>
                    </div>
                </td>
            </tr>
        </tfoot>
        <tbody class="report-content">
            <tr>
                <td class="report-content-cell">
                    <table width="100%" style=" border-top: 2px dashed; border-collapse: collapse;" cellpadding="12">
                        <tbody>
                            <tr>
                                <th style="text-align: center;">LAPORAN PENJUALAN BULANAN</th>
                            </tr>
                        </tbody>
                    </table>
                    <div>
                        TAHUN : <?= $tahun ?>
                    </div>
                    <div>
                        BULAN : <?= strtoupper($bulan) ?>
                    </div>
                    <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                        <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                            <th style="text-align: left;" colspan="5">Makanan</th>
                        </thead>
                        <thead style=" border-top-style: double; border-bottom-style: double;">
                            <th width=5% style="text-align: left;">No.</th>
                            <th width=25% style="text-align: left;">Item Menu</th>
                            <th width=5% style="text-align: left;">Unit</th>
                            <th style="text-align: right;">Penjualan Harian Tertinggi</th>
                            <th style="text-align: right;">Total Penjualan</th>
                        </thead>
                        <tbody style="margin-top: 5px ; border-bottom-style: double;">
                            <!-- Data -->
                            <?php
                            $no = 1;
                            foreach ($result as $key => $data) {
                                if ($data->kategori == 'MainCourse') { ?>
                                    <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: left;"> <?= $data->nama_menu ?></td>
                                        <td style="text-align: left;"><?= $data->unit ?></td>
                                        <td style="text-align: right;"><?php if ($data->penjualan_harian_tertinggi != 0) {
                                            echo $data->penjualan_harian_tertinggi;
                                        } ?></td>
                                        <td style="text-align: right;"><?php if ($data->total_penjualan != 0) {
                                            echo $data->total_penjualan;
                                        } ?></td>
                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>

                    <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                        <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                            <th style="text-align: left;" colspan="5">Side Dish</th>
                        </thead>
                        <thead style=" border-top-style: double; border-bottom-style: double;">
                            <th width=5% style="text-align: left;">No.</th>
                            <th width=25% style="text-align: left;">Item Menu</th>
                            <th width=5% style="text-align: left;">Unit</th>
                            <th style="text-align: right;">Penjualan Harian Tertinggi</th>
                            <th style="text-align: right;">Total Penjualan</th>
                        </thead>
                        <tbody style="margin-top: 5px ; border-bottom-style: double;">
                            <!-- Data -->
                            <?php
                            $no = 1;
                            foreach ($result as $key => $data) {
                                if ($data->kategori == 'SideDish') { ?>
                                    <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: left;"> <?= $data->nama_menu ?></td>
                                        <td style="text-align: left;"><?= $data->unit ?></td>
                                        <td style="text-align: right;"><?php if ($data->penjualan_harian_tertinggi != 0) {
                                            echo $data->penjualan_harian_tertinggi;
                                        } ?></td>
                                        <td style="text-align: right;"><?php if ($data->total_penjualan != 0) {
                                            echo $data->total_penjualan;
                                        } ?></td>
                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>

                    <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                        <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                            <th style="text-align: left;" colspan="5">Minuman</th>
                        </thead>
                        <thead style=" border-top-style: double; border-bottom-style: double;">
                            <th width=5% style="text-align: left;">No.</th>
                            <th width=25% style="text-align: left;">Item Menu</th>
                            <th width=5% style="text-align: left;">Unit</th>
                            <th style="text-align: right;">Penjualan Harian Tertinggi</th>
                            <th style="text-align: right;">Total Penjualan</th>
                        </thead>
                        <tbody style="margin-top: 5px ; border-bottom-style: double;">
                            <!-- Data -->
                            <?php
                            $no = 1;
                            foreach ($result as $key => $data) {
                                if ($data->kategori == 'Drink') { ?>
                                    <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: left;"> <?= $data->nama_menu ?></td>
                                        <td style="text-align: left;"><?= $data->unit ?></td>
                                        <td style="text-align: right;"><?php if ($data->penjualan_harian_tertinggi != 0) {
                                            echo $data->penjualan_harian_tertinggi;
                                        } ?></td>
                                        <td style="text-align: right;"><?php if ($data->total_penjualan != 0) {
                                            echo $data->total_penjualan;
                                        } ?></td>
                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<style>
    @page {
        size: A4;
        margin: 11mm 17mm 17mm 17mm;
    }

    @media print {
        footer {
            position: fixed;
            bottom: 0;
        }

        .content-block,
        p {
            page-break-inside: avoid;
        }

        .header {
            position: fixed;
            top: 0px;
            width: 100%;

        }

        html,
        body {
            margin: auto;
            width: 210mm;
            height: 200mm;
        }
    }

    body {
        margin: auto;
        width: 210mm;
        height: 240mm;
        padding-bottom: 50px;
        font-family: Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 12pt;
    }

    h1 {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 30pt;
        font-style: normal;
        font-variant: normal;
        color: #44546A;
        font-weight: 700;
        text-align: center;
        line-height: 23px;
    }

    span {
        font-size: 8pt;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 30px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .result:after {
        content: "";
        display: table;
        clear: both;
    }

    h3 {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 17px;
        font-style: normal;
        font-variant: normal;
        font-weight: 700;
        line-height: 23px;
    }

    p {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 14pt;
        font-style: normal;
        font-variant: normal;
        font-weight: 400;
        line-height: 23px;
    }

    blockquote {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 17px;
        font-style: normal;
        font-variant: normal;
        font-weight: 400;
        line-height: 23px;
    }

    pre {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 11px;
        font-style: normal;
        font-variant: normal;
        font-weight: 400;
        line-height: 23px;
    }

    img {
        height: 1.37in;
        width: 1.46in;
        margin-top: 15px;
    }

    .my-box {
        width: 100px;
        padding: 20px;
        border: 5px solid black;
        border-radius: 25px;
        margin: auto;
    }

    .logo {
        margin: 10px;
        display: flex;
        justify-content: center;
    }

    .text {
        text-align: center;
    }
</style>
<script>
    window.onload = window.print();
</script>

</html>
