@extends('laralite::admin.layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Ticket Scanner'
    ])

    <div class="page-section">
        <scanner-component></scanner-component>
    </div><!-- End page section -->
@endsection
