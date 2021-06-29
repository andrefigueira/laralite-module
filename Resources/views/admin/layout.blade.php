<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ env('APP_NAME') }} CMS</title>
    <link href="/css/admin.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
</head>

<body class="admin">
<div id="app" class="bg-light" v-cloak>
    {{--@include('laralite::admin.sidebar')--}}
    @include('laralite::admin.header')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('laralite::admin.content')
            </div>

        </div><!-- End row -->
    </div><!-- End container -->
</div><!-- End app -->

<script src="/js/admin.js"></script>
<script>
    document.getElementById("sidebar-1").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    document.getElementById("header").style.marginLeft = "0";
    document.getElementById("open-sidebar").style.display = "block";
    document.getElementById("close-sidebar").style.display = "none";

    function openNav() {
        if(x.matches) {
            document.getElementById("sidebar-1").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            document.getElementById("header").style.marginLeft = "0";
            document.getElementById("open-sidebar").style.display = "block";
            document.getElementById("close-sidebar").style.display = "none";
        } else {
            document.getElementById("sidebar-1").style.width = "260px";
            document.getElementById("main").style.marginLeft = "260px";
            document.getElementById("header").style.marginLeft = "260px";
            document.getElementById("open-sidebar").style.display = "none";
            document.getElementById("close-sidebar").style.display = "block";
        }
    }

    function closeNav() {
        document.getElementById("sidebar-1").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        document.getElementById("header").style.marginLeft = "0";
        document.getElementById("open-sidebar").style.display = "block";
        document.getElementById("close-sidebar").style.display = "none";
    }
    var x = window.matchMedia("(max-width: 800px)")
    openNav(x) // Call listener function at run time
    x.addListener(openNav) // Attach listener function on state changes

</script>
</body>
</html>


