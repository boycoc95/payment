<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{route('payWithMomo')}}" method="POST">
        @csrf
        <button>Thanh Toán Momo</button>
    </form>
</body>
</html>