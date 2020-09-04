@extends('laralite::admin.layout')

@section('content')
    <users-form-component type="{{ $type ?? null }}" :user="{{ $user ?? '{}' }}"></users-form-component>
@endsection
