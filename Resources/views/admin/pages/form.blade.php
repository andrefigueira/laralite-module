@extends('laralite::admin.layout')

@section('content')
    <pages-form-component type="{{ $type ?? null }}" :page="{{ $page ?? '{}' }}"></pages-form-component>
@endsection
