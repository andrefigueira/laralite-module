@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Pages',
        'buttons' => [
            [
                'label' => 'Create page',
                'href' => '/admin/pages/create'
            ],
        ],
    ])

    <div class="page-section">
        <pages-component></pages-component>
    </div><!-- End page section -->
@endsection
