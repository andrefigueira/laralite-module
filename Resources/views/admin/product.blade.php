@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Products',
        'buttons' => [
            [
                'label' => 'Create product',
                'href' => '/admin/product/create'
            ],
        ],
    ])

    <div class="page-section">
        <product-component></product-component>
    </div><!-- End page section -->
@endsection
