@extends('laralite::admin.layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Reporting'
    ])

    <div class="page-section">
        <reporting-component title="Role Management"></reporting-component>
    </div><!-- End page section -->
@endsection


