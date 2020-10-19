@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Users',
        'buttons' => [
            [
                'label' => 'Create user',
                'href' => '/admin/users/create'
            ],
        ],
    ])

    <div class="page-section">
        <users-component></users-component>
    </div><!-- End page section -->
@endsection
