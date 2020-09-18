@extends('laralite::admin.layout')

@section('content')
    <product-form-component type="{{ $type ?? null }}" :product="{{ $product ?? '{}' }}"></product-form-component>
@endsection
