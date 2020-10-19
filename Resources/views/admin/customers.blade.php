@extends('laralite::admin.layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Customers'
    ])

    <div class="page-section">
        <customers-component></customers-component>
    </div><!-- End page section -->
@endsection
