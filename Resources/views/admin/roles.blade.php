@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Roles',
        'buttons' => [
            [
                'label' => 'New Role',
                'href' => '/admin/roles/create'
            ],
        ],
    ])

    <div class="page-section">
        <roles-component title="Role Management"></roles-component>
    </div><!-- End page section -->
@endsection
