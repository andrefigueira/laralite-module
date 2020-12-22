@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Discounts',
        'buttons' => [
            [
                'label' => 'Create discount',
                'href' => '/admin/discounts/create'
            ],
        ],
    ])

    <div class="page-section">
        <discount-component></discount-component>
    </div><!-- End page section -->
@endsection
