@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Product Categories',
        'buttons' => [
            [
                'label' => 'Create product category',
                'href' => '/admin/product-category/create'
            ],
        ],
    ])

    <div class="page-section">
        <product-category-component></product-category-component>
    </div><!-- End page section -->
@endsection
