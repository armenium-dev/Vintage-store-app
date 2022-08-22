<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style type="text/css">
        body {background: #fff url(pdf_bg.jpg) no-repeat center top; background-size: cover;}
    </style>
</head>
<body>
<img src="./pdf_bg.jpg">
<table class="table table-bordered">
    @foreach($mystery_boxes as $mystery_box)
        <tr>
            <td>{!! $mystery_box['product_id'] !!}</td>
            <td>{!! $mystery_box['price'] !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
