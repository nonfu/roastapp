<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <title>Roast</title>

    <script type='text/javascript'>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

<div id="app">
    <router-view></router-view>
</div>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script src="https://webapi.amap.com/maps?v=1.4.8&key=33c20882595f1fecc2d31c8c73a38da7"></script>

</body>
</html>