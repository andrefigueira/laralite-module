@extends('laralite::admin.layout')

@section('content')
    <templates-form-component type="{{ $type ?? null }}" :template="{{ $template ?? '{}' }}"></templates-form-component>
@endsection
