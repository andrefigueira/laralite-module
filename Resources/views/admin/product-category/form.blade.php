@extends('laralite::admin.layout')

@section('content')
    <product-category-form-component type="{{ $type ?? null }}" :product-category="{{ $productCategory ?? '{}' }}"></product-category-form-component>
@endsection
