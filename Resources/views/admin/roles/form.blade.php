@extends('laralite::admin.layout')

@section('content')
    <roles-form-component type="{{ $type ?? null }}" :role="{{ $role ?? '{}' }}"></roles-form-component>
@endsection
