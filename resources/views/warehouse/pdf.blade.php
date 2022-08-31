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
                background: #fff url({!! asset('img/pdf_bg.jpg') !!}) no-repeat center top;
                background-size: contain;
                font-family: 'Raleway', 'Helvetica', sans-serif;
                font-weight: 700;
            }
            h1,
            .savings .value,
            .savings .title,
            h4,
            p {margin: 0; padding: 0; text-transform: uppercase; text-align: center;}
            h1,
            .savings .value,
            .savings .title,
            h4 {font-weight: 900; line-height: 1;}
            h1 {font-size: 19pt; margin-bottom: 120px;}
            .savings .title {font-size: 24pt;}
            .savings .value {font-size: 70pt; border-bottom: 5px solid #000;}
            h4 {font-size: 26pt;}
            p {font-size: 22pt; font-weight: 800;}
            main {padding: 160px 300px 0; background-color: rgba(0, 200, 0, 0.2);}
            main .inner {border: 1px solid #c00; background-color: rgba(255, 255, 255, 0.5);}
            table {}
            table thead th,
            table tfoot {text-transform: uppercase; font-weight: 900;}
            table thead th {font-size: 16pt; padding-bottom: 10px;}
            table td {}
            table tbody td {vertical-align: bottom; line-height: 1; padding: 20px;}
            table .b-right {border-right: 5px solid #000;}
            table .p-left {padding-left: 65px;}
            table .title {width: 65%; font-size: 11pt;}
            table .price {width: 35%; font-size: 14pt;}
            table tfoot td {border-top: 5px solid #000; font-size: 15pt; padding: 30px 20px;}
            .savings,
            .footer {text-align: center;}
        </style>
    </head>
    <body>
        <main>
            <div class="inner">
                <h1>Thank you for<br>your order</h1>
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
                    <h4>Order again</h4>
                    <p>10% off yur next box<br>Use code: <u>Mystery</u></p>
                </div>
            </div>
        </main>
    </body>
</html>
