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
            h1, h2, h3, p {margin: 0; padding: 0; text-transform: uppercase; text-align: center;}
            h1, h2, h3 {font-weight: 900; line-height: 1;}
            h1 {font-size: 20pt;}
            main {padding: 150px 300px 0; background-color: rgba(0, 200, 0, 0.2);}
            main .inner {border: 1px solid #c00; background-color: rgba(255, 255, 255, 0.5);}
            .table-data * {font-size: 16pt;}
            .table-data {
                /*position: absolute;
                z-index: 1;
                left: 25%;
                top: 25%;*/
                width: 900px;
                border: 1px solid #00c;
            }
            table thead,
            table tfoot {text-transform: uppercase; font-weight: 900;}
            table .title {width: 70%;}
            table .price {width: 30%; text-align: right;}
        </style>
    </head>
    <body>
        <main>
            <div class="inner">
                <h1>Thank you for<br>your order</h1>
                <div class="table-data">
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
                                <td class="title">{!! $item['title'] !!}</td>
                                <td class="price">&pound;{!! $item['price'] !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total value</td>
                                <td>&pound;{!! $box_total !!}</td>
                            </tr>
                            <tr>
                                <td>Box price</td>
                                <td>&pound;{!! $box_price !!}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="savings">
                    <h3>Total savings:</h3>
                    <h2>&pound;{!! $total_saving !!}</h2>
                </div>
                <div class="footer">
                    <h4>Order again</h4>
                    <p class="p1">10% off yur next box</p>
                    <p class="p2">Use code: <u>Mystery</u></p>
                </div>
            </div>
        </main>
    </body>
</html>
