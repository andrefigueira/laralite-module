<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
{{--    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">--}}

    <title>GuruCMS</title>

{{--    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/dashboard/">--}}

    <!-- Bootstrap core CSS -->
    <link href="/css/app.css" rel="stylesheet">
</head>

<body v-cloak>
<div id="app">
    @include('admin.header')

    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            @include('admin.content')
        </div><!-- End row -->
    </div><!-- End container -->
</div><!-- End app -->

<script src="/js/app.js"></script>
</body>
</html>
