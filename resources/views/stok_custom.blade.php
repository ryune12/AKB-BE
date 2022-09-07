<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table width=90% style="margin: auto;" class="report-container">
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
        <tfoot>
            <tr>
                <th class="report-footer-cell">
                    <div style="text-align: center; margin-bottom: 50px; margin-top: 20px; bottom: 0px; ">

                        <span>Printed <?= @date('M d, Y H:i:s A') ?></span><br>
                        <span>Printed by {{ $user->nama }}</span>
                    </div>
                </th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>
                    

                        <table width="100%" style=" border-top: 2px dashed; border-collapse: collapse;" cellpadding="12">
                            <tbody>
                                <tr>
                                    <th style="text-align: center;">LAPORAN STOK BAHAN</th>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            ITEM MENU : ALL
                        </div>
                        <div>
                            PERIODE: CUSTOM ({{ date_format(date_create($start), 'j M Y') }} s/d {{ date_format(date_create($end), 'j M Y') }})
                        </div>
                        <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                            <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                                <th style="text-align: left;" colspan="6">Makanan</th>
                            </thead>
                            <thead style=" border-top-style: double; border-bottom-style: double;">
                                <th width=5% style="text-align: left;">No.</th>
                                <th width=30% style="text-align: left;">Item Menu</th>
                                <th width=5% style="text-align: left;">Unit</th>
                                <th style="text-align: right;">Incoming Stock</th>
                                <th style="text-align: right;">Remaining Stock</th>
                                <th style="text-align: right;">Waste Stock</th>
                            </thead>
                            <tbody style="margin-top: 5px ; border-bottom-style: double;">
                                <!-- Data -->
                                <?php
                                $no = 1;
                                $index = 0;
                                foreach ($bahan as $key => $data) {
                                    if ($data->kategori == 'MainCourse') { ?>
                                        <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                            <td style="text-align: center;"><?= $no++ ?></td>
                                            <td style="text-align: left;"> <?= $data->nama_bahan ?></td>
                                            <td style="text-align: left;"><?= $data->unit ?></td>
                                            <td style="text-align: right;"><?= $incoming[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $incoming[$index]->jumlah - $penjualan[$index]->jumlah - $waste[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $waste[$index]->jumlah ?></td>
                                        </tr>
                                        
                                <?php }
                                    $index++;
                                }
                                ?>
                            </tbody>
                        </table>
                <br>    
                        <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                            <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                                <th style="text-align: left;" colspan="6">Side Dish</th>
                            </thead>
                            <thead style=" border-top-style: double; border-bottom-style: double;">
                                <th width=5% style="text-align: left;">No.</th>
                                <th width=30% style="text-align: left;">Item Menu</th>
                                <th width=5% style="text-align: left;">Unit</th>
                                <th style="text-align: right;">Incoming Stock</th>
                                <th style="text-align: right;">Remaining Stock</th>
                                <th style="text-align: right;">Waste Stock</th>
                            </thead>
                            <tbody style="margin-top: 5px ; border-bottom-style: double;">
                                <!-- Data -->
                                <?php
                                $no = 1;
                                $index = 0;
                                foreach ($bahan as $key => $data) {
                                    if ($data->kategori == 'SideDish') { ?>
                                    <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: left;"> <?= $data->nama_bahan ?></td>
                                        <td style="text-align: left;"><?= $data->unit ?></td>
                                        <td style="text-align: right;"><?= $incoming[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $incoming[$index]->jumlah - $penjualan[$index]->jumlah - $waste[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $waste[$index]->jumlah ?></td>
                                    </tr>
                                <?php }
                                    $index++;
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <table style="margin-top: 20px;  border-collapse: collapse" cellpadding="5" width=100%>
                            <thead style=" border-top: 1px solid; background-color: #F2F2F2; ">
                                <th style="text-align: left;" colspan="6">Minuman</th>
                            </thead>
                            <thead style=" border-top-style: double; border-bottom-style: double;">
                                <th width=5% style="text-align: left;">No.</th>
                                <th width=30% style="text-align: left;">Item Menu</th>
                                <th width=5% style="text-align: left;">Unit</th>
                                <th style="text-align: right;">Incoming Stock</th>
                                <th style="text-align: right;">Remaining Stock</th>
                                <th style="text-align: right;">Waste Stock</th>
                            </thead>
                            <tbody style="margin-top: 5px ; border-bottom-style: double;">
                                <!-- Data -->
                                <?php
                                $no = 1;
                                $index = 0;
                                foreach ($bahan as $key => $data) {
                                    if ($data->kategori == 'Drink') { ?>
                                <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                    <tr style="padding-bottom: 5px ; border-bottom: 1px dashed">
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: left;"> <?= $data->nama_bahan ?></td>
                                        <td style="text-align: left;"><?= $data->unit ?></td>
                                        <td style="text-align: right;"><?= $incoming[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $incoming[$index]->jumlah - $penjualan[$index]->jumlah - $waste[$index]->jumlah ?></td>
                                            <td style="text-align: right;"><?= $waste[$index]->jumlah ?></td>
                                    </tr>
                                <?php }
                                    $index++;
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
    .row:after {
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
