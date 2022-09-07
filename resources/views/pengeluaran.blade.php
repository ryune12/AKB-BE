<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="margin: 50px;">
        <div class="logo" style="margin-top: -20px; text-align: justify;">
            <img src="{{asset('logo.png')}}" alt="">
            <div style="margin-left: 40px;">
                <h1>ATMA KOREAN BBQ</h1>
                <p style="color: #C00000; margin-top: -20px; text-align: center;">FUN PLACE TO GRILL!</p>
                <p style="text-align: center; margin-top: -20px; font-family: Calibri, sans-serif;">Jl. Babarsari No. 43 Yogyakarta 552181
                    <br>
                    Telp. (0274) 487711 | http://www.atmakoreanbbq.com
                </p>
            </div>
        </div>

        <table width="100%" style=" border-top: 2px dashed; border-collapse: collapse;" cellpadding="12">
            <tbody>
                <tr>
                    <th style="text-align: center;">LAPORAN PENGELUARAN BULANAN</th>
                </tr>
            </tbody>
        </table>
        <?php if ($tipe == 'BULANAN') { ?>
            <div>
                TAHUN : <?= $tahun ?>
            </div>
        <?php } else { ?>
            <div>
                Tahun : <?= $from ?> s/d : <?= $to ?>
            </div>
        <?php } ?>
        <table style="margin-top: 20px; border-top-style: double; border-bottom-style: double; border-collapse: collapse" cellpadding="5" width=100%>
            <thead style=" border-bottom: 1px solid;">
                <th>No.</th>
                <th style="text-align: left;">Bulan</th>
                <th style="text-align: right;">Makanan</th>
                <th style="text-align: right;">Side Dish</th>
                <th style="text-align: right;">Minuman</th>
                <th style="text-align: right;">Total Pengeluaran</th>
            </thead>
            <tbody style="margin-top: 5px ;">
                <!-- Data -->
                <?php $no = 1;
                foreach ($result as $key => $data) { ?>
                    <tr style="padding-bottom: 5px ;">
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td style="text-align: left;"> <?= ($tipe == "BULANAN") ? $data->Bulan : $data->Tahun ?></td>
                        <td style="text-align: right;">Rp <?= number_format((float)$data->Makanan, 2, ',', '.'); ?></td>
                        <td style="text-align: right;">Rp <?= number_format((float)$data->SideDish, 2, ',', '.'); ?></td>
                        <td style="text-align: right;">Rp <?= number_format((float)$data->Minuman, 2, ',', '.'); ?></td>
                        <td style="text-align: right;">Rp <?= number_format((float)$data->TotalPengeluaran, 2, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 50px ">
            
            <span>Printed <?= @date('M d, Y H:i:s A') ?></span><br>
            <span>Printed by <?= $user->nama ?></span>
        </div>
    </div>
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