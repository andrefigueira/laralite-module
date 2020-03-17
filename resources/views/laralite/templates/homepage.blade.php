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
    <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
</head>

<body>
<div id="app" style="
background-image: url(/images/background.jpg);
background-size: cover;
background-repeat: no-repeat;
background-attachment: fixed;
" v-cloak>
    <side-nav></side-nav>

    <div class="row">
        <div class="col-md-12">
            <img class="logo" src="/images/trap-music-museum-logo.png" alt="">

            <div class="row mb-5">
                <div class="col-md-2 offset-5">
                    <a href="" class="btn btn-secondary call-to-action">Purchase tickets <i class="fas fa-chevron-right"></i></a>
                    <a href="" class="btn btn-secondary call-to-action">Escape room <i class="fas fa-chevron-right"></i></a>
                </div><!-- End col -->
            </div><!-- End row -->
        </div><!-- End col -->
    </div><!-- End row -->

    <div class="spacer" style="height: 30rem"></div>

    <div class="row">
        <div class="col-md-12 image-section">
            <h3 class="image-section-title">
                The Trap Music Museum isn't just about music but also about the culture that <span class="highlight-text">Inspires</span>
            </h3>
            <h4 class="image-section-subtitle">- The Source</h4>

            <img
                style="
                position: absolute;
                right: 0;
                height: 50rem;
                width: auto;
                "
                src="/images/parralax-image-1.png" alt="Parralex Image 1" class="parralax-image-1">

            <div class="spacer" style="height: 52rem"></div>

            <img
                style="
                position: absolute;
                left: 0;
                height: 70rem;
                width: auto;
                "
                src="/images/parralax-image-2.png" alt="Parralex Image 2" class="parralax-image-2">

            <img
                style="
                position: absolute;
                right: 0;
                margin: 20rem 0 0 0;
                height: 50rem;
                width: auto;
                "
                src="/images/parralax-image-3.png" alt="Parralex Image 3" class="parralax-image-3">

            <h3 class="image-section-supplemental-title">MUSEUM HOURS</h3>
            <p class="image-section-supplemental-content">
                Thursday (HAPPY HOUR)  4pm-10pm<br>
                FRIDAY 4pm - 12AM<br>
                Saturday 12pm - 12am<br>
                Sunday 2pm -10pm<br>
                <a href="/" class="btn btn-outline-danger">More Information <i class="fas fa-chevron-right"></i></a>
            </p>
        </div><!-- End col -->
    </div><!-- End row -->

    <div class="spacer" style="height: 52rem"></div>

    <div class="row">
        <div class="col-md-12 image-section">
            <h3 class="image-section-title font-size-3">
                ESCAPE ROOM HOURS
            </h3>
            <h4 class="image-section-subtitle font-size-1">
                Open 7 Days a week<br>
                Click here to book
            </h4>
        </div><!-- End col -->
    </div><!-- End row -->

    <portal-renderer :page="{{ $page ? $page : '{}' }}"></portal-renderer>
</div><!-- End app -->

<script src="/js/frontend.js"></script>
</body>
</html>
