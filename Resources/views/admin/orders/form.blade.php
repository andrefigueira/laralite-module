@extends('laralite::admin.layout')

@section('content')
    <orders-form-component type="{{ $type ?? null }}" :order="{{ $order ?? '{}' }}"></orders-form-component>
@endsection
