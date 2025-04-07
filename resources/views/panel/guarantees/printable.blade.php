<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>پرینت لیبل گارانتی</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            direction: rtl;
            font-family: sans-serif;
            background-color: #f3f3f3;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .label {
            position: relative;
            background-color: white;
            padding: 0;
            text-align: right;
            width: 600px;
            max-width: 90vw;
        }

        .label img {
            width: 100%;
            display: block;
        }

        .info-text {
            position: absolute;
            right: 260px;
            font-size: 13px;
            color: #000;

        }


        .product     { top: 486px; }
        .serial      { top: 508px; }
        .code        { top: 530px; }
        .tracking    { top: 553px; }
        .start       { top: 576px; }
        .end         { top: 598px; }
        .importer    { top: 616px; }


        .print-btn {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-family: Vazirmatn, sans-serif;
        }
        body  {
            font-family: Vazirmatn, sans-serif;

        }

        @media print {
            body * {
                visibility: hidden;
                font-family: Vazirmatn, sans-serif;

            }
            .label, .label * {
                visibility: visible;
            }
            .label {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                margin: auto;
                width: 100%;
                padding: 0;
                background: none;
                box-shadow: none;
                border-radius: 0;
            }
            /*.product     { top: 600px; }*/
            /*.serial      { top: 628px; }*/
            /*.code        { top: 650px; }*/
            /*.tracking    { top: 680px; }*/
            /*.start       { top: 705px; }*/
            /*.end         { top: 732px; }*/
            /*.importer    { top: 755px; }*/
            .product     { top: 522px; }
            .serial      { top: 548px; }
            .code        { top: 570px; }
            .tracking    { top: 593px; }
            .start       { top: 620px; }
            .end         { top: 642px; }
            .importer    { top: 660px; }
            .info-text {
                right: 320px;
                font-size: 15px;
                color: #000;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
    <button class="print-btn" onclick="window.print()">پرینت لیبل</button>
    <div class="label" id="label">
        <img src="{{ asset('/assets/images/guarantee_label.png') }}" alt="لیبل_گارانتی">

{{--        @dd($guarantee)--}}
        <div class="info-text product">{{$guarantee->product->title}}</div>
        <div class="info-text serial">{{$guarantee->serial_number}}</div>
        <div class="info-text code">{{$guarantee->product_identifier}}</div>
        <div class="info-text tracking">{{$guarantee->tracking_code}}</div>
        <div class="info-text start">{{verta($guarantee->start_time)->format('Y/m/d')}}</div>
        <div class="info-text end">{{verta($guarantee->expire_time)->format('Y/m/d')}}</div>
        <div class="info-text importer">{{$guarantee->importing_company}}</div>
    </div>


</div>
</body>
</html>
