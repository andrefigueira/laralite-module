@extends('laralite::admin.layout')

@section('content')
    <permissions-form-component type="{{ $type ?? null }}" :permission="{{ $permission ?? '{}' }}"></permissions-form-component>
@endsection
