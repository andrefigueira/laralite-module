<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ env('APP_NAME') }} CMS</title>
    <link href="/css/admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
</head>

<body class="admin">
<div id="app" class="bg-light" v-cloak>
    {{--@include('laralite::admin.sidebar')--}}
    @include('laralite::admin.header')

    <div class="container-fluid sticky-top-correction">
        <div class="row">
                @include('laralite::admin.content')
        </div><!-- End row -->
    </div><!-- End container -->
</div><!-- End app -->

<script src="/js/admin.js"></script>
</body>
</html>
