<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>موجودی انبار</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            height: 100vh;
            text-align: center;
            box-sizing: border-box;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            margin: 20px auto;
        }

        .header-container h4 {
            flex: 1;
            text-align: right;
        }

        .header-container h2 {
            flex: 2;
            text-align: center;
        }

        .header-container h4:last-child {
            text-align: left;
        }

        img {
            max-width: 180px;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            text-align: center;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        thead {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<img src="{{ asset('/assets/images/header-logo.png') }}" alt="لوگو">
<table>
    <tr style="width: 100%">
        <td style="width: 30%; text-align: right;border: transparent 0px solid">انبار مرکزی</td>
        <td style="width: 40%;font-size: 1.5rem;font-weight: bold; text-align: center;border: transparent 0px solid">
            موجودی انبار
        </td>
        <td style="width: 30%; text-align: left;border: transparent 0px solid">
            زمان: {{ verta()->format('H:i %Y/%m/%d') }}</td>
    </tr>
</table>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>شناسه کالا</th>
        <th>شرح کالا</th>
        <th>دسته بندی کالا</th>
        <th>برند کالا</th>
        <th>موجودی کالا</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->category->name ?? '-' }}</td>
            <td>{{ $product->brand->name ?? '-' }}</td>
            <td>{{ $product->tracking_codes_count ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
