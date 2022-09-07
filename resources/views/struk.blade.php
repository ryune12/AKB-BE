<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php date_default_timezone_set('Asia/Jakarta'); ?>
    <div style="margin: 50px;">
        <div class="logo" style="margin-top: -20px;">
            <img src="{{ asset('logo.png') }}" alt="">
            <div>
                <h1>ATMA KOREAN BBQ</h1>
                <p style="color: #C00000; margin-top: -20px; text-align: center;">FUN PLACE TO GRILL!</p>
                <p style="text-align: center; margin-top: -20px; font-family: Calibri, sans-serif;">Jl. Babarsari No. 43
                    Yogyakarta
                    <br>
                    552181
                    <br>
                    Telp. (0274) 487711
                </p>
            </div>
        </div>

        <table width="100%" style=" border-top: 2px dashed; border-collapse: collapse" cellpadding="4">
            <tbody>
                <tr>
                    <th style="text-align: left;">Receipt #</th>
                    <td>AKB-<?= @date('dmy') ?>-50</td>
                    <th style="text-align: left;">Date</th>
                    <td><?= @date('m/d/y') ?></td>
                </tr>
                <tr>
                    <th style="text-align: left;">Waiter</th>
                    <td><?= $reservasi->nama ?></td>
                    <th style="text-align: left;">Time</th>
                    <td><?= @date('H:i') ?></td>
                </tr>
                <tr style="border-top: 2px dashed;">
                    <th style="text-align: left;">Table #</th>
                    <td>{{ $meja->nomor_meja }}</td>
                    <th style="text-align: left;">Customer</th>
                    <td>{{ $meja->nama }}</td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 20px; border-top-style: double; border-bottom-style: double; border-collapse: collapse" cellpadding="5" width=100%>
            <thead style=" border-bottom: 1px solid;">
                <th>Qty</th>
                <th style="text-align: left;">Nama Menu</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Sub Total</th>
            </thead>
            <tbody style="margin-top: 5px ; ">
                <!-- Data -->
                <?php
                $subtotal = 0;
                $item = 0;
                $qty = 0;
                if (is_array($detail) || is_object($detail)) {
                    foreach ($detail as $key => $data) {

                        $subtotal += $data->subtotal;
                        $item++;
                        $qty += $data->kuantitas;
                        ?>
                            <tr style="padding-bottom: 5px ;">
                                <td style="text-align: center;">{{ $data->kuantitas }}</td>
                                <td style="text-align: left;">{{ $data->nama_menu }}</td>
                                <td style="text-align: right;">Rp <?= number_format($data->harga, 0, ',', '.') ?></td>
                                <td style="text-align: right;">Rp <?= number_format($data->subtotal, 0, ',', '.') ?></td>
                            </tr>
                <?php
                    }
                }
                ?>
                <!-- Sumary  -->
                <tr style="border-top: 2px dashed;">
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;">Subtotal</td>
                    <td style="text-align: right;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;">Service 5%</td>
                    <td style="text-align: right;">Rp <?= number_format($subtotal * 0.05, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;">Tax 10%</td>
                    <td style="text-align: right;">Rp <?= number_format($subtotal * 0.1, 0, ',', '.') ?></td>
                </tr>
                <tr style="border-top: 2px dashed;">
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><b>Total</b></td>
                    <td style="text-align: right;"><b>Rp <?= number_format($subtotal + $subtotal * 0.1 + $subtotal * 0.05, 0, ',', '.') ?></b></td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: right; margin-top: 5px ">
            <span>Total Qty: {{ $qty }}</span><br>
            <span>Total Item: {{ $item }}</span>
        </div>
        <div style="text-align: right; margin-top: 20px ">
            <span>Printed <?= date('M d, Y') ?> <?= @date('H:i:s A') ?> </span><br>
            <span>Cashier: {{ $user->nama }}</span>
        </div>
        <div style="font-size: 10pt;text-align: center; margin-top: 20px; border-top: 1px dashed; border-bottom: 1px dashed;">
            <b>THANK YOU FOR YOUR VISIT</b>
        </div>
    </div>
</body>
<style>
    body {
        margin: auto;
        width: 148mm;
        height: 210mm;
        padding-bottom: 50px;
        font-family: Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 12pt;
    }

    h1 {
        font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        font-size: 25pt;
        font-style: normal;
        font-variant: normal;
        color: #44546A;
        font-weight: 700;
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
        height: 1.35in;
        width: 1.53in;
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
        margin: auto;
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
