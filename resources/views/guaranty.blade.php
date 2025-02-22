<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
          type="text/css"/>

    <style>


        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Vazirmatn, sans-serif;
        }

        input {
            width: 500px;
            height: 40px;
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: right;
            font-size: 1.3rem;
        }

        button {
            width: 100px;
            background: #1c7cff;
            border: none;
            border-radius: 2px;
            margin-right: 7px;
            height: 44px;
            font-size: 15px;
            color: white;
            font-family: Vazirmatn, sans-serif;
            cursor: pointer;
        }

        div {
            text-align: center;
        }

        .text-danger {
            color: red;
            display: none;

        }
        .error {
            color: red;
            display: none;

        }
    </style>

</head>
<body>
<div>
    <h3>استعلام اصالت گارانتی کالا براساس سریال</h3>
    <input type="text" id="serialInput">    <button id="checkWarranty">استعلام</button>
    <div class="text-danger">گارانتی با این شماره سریال یافت نشد!</div>
    <div class="error">ابتدا شماره سریال را وارد کنید</div>
</div>
</body>
<script src="{{asset('/assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#checkWarranty").click(function () {
            var serial = $("#serialInput").val().trim();

            $(".text-danger, .error").hide();

            if (serial === "") {
                $(".error").show();
            } else {
                $(".text-danger").show();
            }
        });
    });
</script>

</html>
