@extends('./admin/layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Pages</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="/admin/pages/create" class="btn btn-sm btn-outline-secondary">Create page</a>
            </div>
        </div><!-- End toolbar -->
    </div><!-- End content bar -->

    <pages :items="{{ $pages }}"></pages>
@endsection
