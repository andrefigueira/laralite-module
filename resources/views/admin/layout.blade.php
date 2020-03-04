<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>GuruCMS</title>
    <link href="/css/app.css" rel="stylesheet">
</head>

<body>
<div id="app" class="bg-light" v-cloak>
    @include('admin.header')

    <div class="container-fluid sticky-top-correction">
        <div class="row">
            @include('admin.sidebar')

            @include('admin.content')
        </div><!-- End row -->
    </div><!-- End container -->
</div><!-- End app -->

<script src="/js/app.js"></script>
</body>
</html>
