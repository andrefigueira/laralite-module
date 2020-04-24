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
    <link href="https://fonts.googleapis.com/css?family=Lato:300i,400,400i,700,700i,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="/images/trap-music-museum-logo.png"/>
</head>

<body>
<div id="app" style="
background-image: url(/images/background.jpg);
background-size: cover;
background-repeat: no-repeat;
background-attachment: fixed;
" v-cloak>
    <div class="wrapper">
        <page-loaded></page-loaded>
        <top-nav></top-nav>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <portal-renderer :page="{{ $page ? $page : '{}' }}"></portal-renderer>
            </div><!-- End col -->
        </div><!-- End row -->

        <footer-component></footer-component>
    </div>
</div><!-- End app -->

<script src="/js/app.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-164486427-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());


    gtag('config', 'UA-164486427-1');
</script>

</body>
</html>
