@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Permissions',
        'buttons' => [
            [
                'label' => 'New Permission',
                'href' => '/admin/permissions/create'
            ],
        ],
    ])

    <div class="page-section">
        <permissions-component title="Permission Management"></permissions-component>
    </div><!-- End page section -->
@endsection
