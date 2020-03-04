@extends('./admin/layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="/admin/users/create" class="btn btn-sm btn-outline-secondary">Create user</a>
            </div>
        </div><!-- End toolbar -->
    </div><!-- End content bar -->

    <div class="page-section">
        <users></users>
    </div><!-- End page section -->
@endsection
