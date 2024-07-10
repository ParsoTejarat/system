<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>دستور پرداخت</title>
</head>
<style>
    body {

        padding: 1rem
    }

    .img-logo {

    }

    .h3-logo {
        position: absolute;
        top: 10px;
        right: 40%
    }

    .title {
        position: absolute;
        top: 60px;
        right: 45%;
    }

    .number {
        position: absolute;
        top: 20px;
        left: 15%
    }

    .date {
        position: absolute;
        top: 60px;
        left: 15%
    }

    .box-top {
        border: 1px solid black;
        width: 100%;
        height: 100px;
    }

    .box-main {
        border: 1px solid black;
        border-top: 0px;
        width: 100%;
        height: 800px;
    }
</style>
<body style=" position: relative;">
<div class="box-top">
    <img src="{{public_path('/assets/images/parsoo_logo.png')}}"  alt="parsoo" style="margin-right: 5rem; margin-top: 5rem;width:1rem">
    <h3 style="position: absolute;top: 10px;right: 40%">بازرگانی پرسو تجارت ایرانیان</h3>
    <h3 style="position: absolute;
        top: 60px;
        right: 45%;">دستور پرداخت</h3>
    <h4 style="position: absolute;top: 20px;left: 15%"> شماره:{{$date}}</h4>
    <h4 style="position: absolute;top: 60px;left: 15%"> تاریخ:{{$orderPayment->number}}</h4>
</div>
<div class="box-main">

</div>
</body>
</html>
