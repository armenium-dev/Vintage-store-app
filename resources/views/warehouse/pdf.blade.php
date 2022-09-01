<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700;800;900&display=swap" rel="stylesheet">
        <style type="text/css">
            html, body {margin: 0; padding: 0;}
            body {
                background: #fff url({!! asset('img/pdf_bg2.jpg') !!}) no-repeat center top;
                background-size: contain;
                font-family: 'Raleway', 'Helvetica', sans-serif;
                font-weight: 700;
            }
            .main-title,
            .savings,
            .footer {margin: 0; padding: 0; text-transform: uppercase; text-align: center; font-weight: 900; line-height: 1;}

            @if($box_items_count > 5)
            main {padding: 100px 200px 0;}
            .main-title {margin-bottom: 40px;}
            table .p-left {padding-left: 40px;}
            table .title {width: 80%; font-size: 9pt;}
            table .price {width: 20%; font-size: 12pt;}
            table tbody td {padding: 10px;}
            table thead th {font-size: 13pt;}
            table tfoot td {font-size: 13pt; padding: 20px 10px;}
            .savings {margin-top: 15px;}
            .savings .value {font-size: 40pt;}
            @else
            main {padding: 120px 300px 0; background-color: rgba(0, 200, 0, 0);}
            .main-title {margin-bottom: 100px;}
            table .p-left {padding-left: 65px;}
            table .title {width: 70%; font-size: 11pt;}
            table .price {width: 30%; font-size: 14pt;}
            table tbody td {padding: 20px;}
            table thead th {font-size: 15pt;}
            table tfoot td {font-size: 15pt; padding: 30px 20px;}
            .savings {margin-top: 30px;}
            .savings .value {font-size: 60pt;}
            @endif

            .main-title {font-size: 19pt;}
            .savings .title {font-size: 22pt;}
            .savings .value {border-bottom: 5px solid #000;}
            .footer .title {font-size: 22pt;}
            .footer .desc {font-size: 14pt;}
            main .inner {border: 0px solid #c00; background-color: rgba(255, 255, 255, 0);}
            table thead th,
            table tfoot {text-transform: uppercase; font-weight: 900;}
            table thead th {padding-bottom: 10px;}
            table tbody td {vertical-align: bottom; line-height: 1; border-bottom: 1px solid #000;}
            table .b-right {border-right: 5px solid #000;}
            table tfoot td {border-top: 5px solid #000;}
            .savings,
            .footer {text-align: center;}
        </style>
    </head>
    <body>
        <main>
            <div class="inner">
                <div class="main-title">Thank you for<br>your order</div>
                <table>
                    <thead>
                        <tr>
                            <th>Items</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($box_items as $item)
                        <tr>
                            <td class="title b-right">{!! $item['title'] !!}</td>
                            <td class="price p-left">&pound;{!! $item['price'] !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="b-right">Total value</td>
                            <td class="p-left">&pound;{!! $box_total !!}</td>
                        </tr>
                        <tr>
                            <td class="b-right">Box price</td>
                            <td class="p-left">&pound;{!! $box_price !!}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="savings">
                    <div class="title">Total savings:</div>
                    <div class="value">&pound;{!! $total_saving !!}</div>
                </div>
                <div class="footer">
                    <div class="title">Order again</div>
                    <div class="desc">10% off yur next box<br>Use code: <u>Mystery</u></div>
                </div>
            </div>
        </main>
    </body>
</html>
