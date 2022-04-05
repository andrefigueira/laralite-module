@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Subscriptions',
        'buttons' => [
            [
                'label' => 'New Subscription',
                'href' => '/admin/subscriptions/create'
            ],
        ],
    ])

    <div class="page-section">
        <subscriptions-component title="Subscription Management"></subscriptions-component>
    </div><!-- End page section -->
@endsection
