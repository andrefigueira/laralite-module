@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Navigation',
        'buttons' => [
            [
                'label' => 'Create navigation',
                'href' => '/admin/navigation/create'
            ],
        ],
    ])

    <div class="page-section">
        <navigation-component></navigation-component>
    </div><!-- End page section -->
@endsection
