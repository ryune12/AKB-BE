<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QRCode</title>
</head>
<body>
    <div class="logo" style="margin-top: 20px; margin-bottom: 20px" ><img src="http://127.0.0.1:8000/Picture2.svg" alt=""></div>

    <div class="my-box">
        <img style="margin: auto" src="http://127.0.0.1:8000/qrcode{{$id}}.svg" alt="">
    </div>
    <p class="text">Printed <b>{{\Carbon\Carbon::now()}}</b></p>
    <p class="text">Printed By {{ $user->nama }}</p>
    <hr class="text" style="border-top: dotted 1px; width: 50mm" />
    <p class="text"><b>FUN PLACE TO GRILL</b></p>
    <hr class="text" style="margin-bottom: 20px; border-top: dotted 1px; width: 50mm" />
</body>
<style>
     body{
         margin: 0;
        width: 74mm;
        height: 105mm;
        padding-bottom: 50px;
        border: 5px solid black;
    }
    img {
        border-radius: 4px;
        width: 100px;
    }
    .my-box{
        width: 100px;
        padding: 20px;
        border: 5px solid black;
        border-radius: 25px;
        margin: auto;
    }
    .logo {
        margin: auto;
        display: flex; justify-content: center;
    }
    
    .text {
        text-align:center;
    }
}
</style>
<script>
    window.onload = window.print();
</script>
</html>
