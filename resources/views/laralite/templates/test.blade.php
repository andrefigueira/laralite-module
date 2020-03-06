<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Frontend</title>
    <link href="/css/frontend.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
</head>

<body>
<div id="app" v-cloak>
    <div class="wrapper" style="background-image: url('/images/background.jpg');">
        <portal-renderer :page="{{ $page ? $page : '{}' }}"></portal-renderer>
    </div>
</div><!-- End app -->

<script src="/js/frontend.js"></script>
</body>
</html>
