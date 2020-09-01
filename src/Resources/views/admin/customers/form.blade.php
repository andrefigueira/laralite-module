@extends('laralite::admin.layout')

@section('content')
    <customers-form-component type="{{ $type ?? null }}" :customer="{{ $customer ?? '{}' }}"></customers-form-component>
@endsection
