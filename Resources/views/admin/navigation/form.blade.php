@extends('laralite::admin.layout')

@section('content')
    <navigation-form-component type="{{ $type ?? null }}" :navigation="{{ $navigation ?? '{}' }}"></navigation-form-component>
@endsection
