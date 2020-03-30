<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $page->meta->description }}">
    <meta name="keywords" content="{{ $page->meta->keywords }}">
    <meta name="author" content="{{ $page->meta->author }}">
    <title>{{ $page->meta->title }}</title>
    <link href="/css/frontend.css" rel="stylesheet">
    <link href="/css/contents.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
</head>

<body>
<div id="app" v-cloak>
    <div class="wrapper" style="background-image: url('/images/background.jpg');">
        <page-loaded></page-loaded>
        <top-nav></top-nav>

        <div class="row">
            <div class="col-md-8 offset-2">
                <portal-renderer :page="{{ $page ? $page : '{}' }}"></portal-renderer>
            </div><!-- End col -->
        </div><!-- End row -->
    </div>
</div><!-- End app -->

<script src="/js/app.js"></script>
</body>
</html>
