@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Templates',
        'buttons' => [
            [
                'label' => 'Create template',
                'href' => '/admin/templates/create'
            ],
        ],
    ])

    <div class="page-section">
        <templates-component></templates-component>
    </div><!-- End page section -->
@endsection
