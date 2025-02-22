<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>استعلام اصالت گارانتی کالا براساس سریال</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css"/>

    <style>
        /* استایل‌های عمومی برای بدنه و html */
        body, html {
            height: 100%;
            margin: 0;
            font-family: Vazirmatn, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        /* استایل برای div اصلی */
        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        /* استایل برای عنوان */
        h3 {
            margin-bottom: 20px;
            color: #333;
        }

        /* استایل برای input */
        input {
            width: 100%;
            height: 40px;
            padding: 0 10px;
            margin-bottom: 20px;
            text-align: right;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: Vazirmatn, sans-serif;
        }

        /* استایل برای دکمه */
        button {
            width: 120px;
            height: 44px;
            background-color: #1c7cff;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: Vazirmatn, sans-serif;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* استایل برای پیام‌های خطا */
        .text-danger, .error {
            color: red;
            font-size: 1rem;
            display: none;
            margin-top: 10px;
        }
    </style>

</head>
<body>
<div class="container">
    <h3>استعلام اصالت گارانتی کالا براساس سریال</h3>
    <input type="text" id="serialInput" placeholder="شماره سریال را وارد کنید">
    <button id="checkWarranty">استعلام</button>
    <div class="text-danger">گارانتی با این شماره سریال یافت نشد!</div>
    <div class="error">ابتدا شماره سریال را وارد کنید</div>
</div>

<script src="{{asset('/assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#checkWarranty").click(function () {
            var serial = $("#serialInput").val().trim();

            $(".text-danger, .error").hide();  // ابتدا تمام پیام‌ها را مخفی می‌کنیم

            if (serial === "") {
                $(".error").show();  // اگر ورودی خالی باشد، پیام خطا نمایش داده می‌شود
            } else {
                $(".text-danger").show();  // اگر ورودی پر باشد، پیام گارانتی نمایش داده می‌شود
            }
        });
    });
</script>
</body>
</html>
