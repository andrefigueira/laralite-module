@extends('laralite::admin.layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Orders'
    ])

    <div class="page-section">
        <orders-component></orders-component>
    </div><!-- End page section -->
@endsection
