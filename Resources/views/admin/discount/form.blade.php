@extends('laralite::admin.layout')

@section('content')
    <discount-form-component type="{{ $type ?? null }}" :discount="{{ $discount ?? '{}' }}"></discount-form-component>
@endsection
