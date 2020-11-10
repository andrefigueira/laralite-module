@extends('laralite::admin.layout')

@section('content')
    <users-form-component type="{{ $type ?? null }}" :user="{{ $user ?? '{}' }}" :userroles="{{ $userRoles ?? '[]' }}"></users-form-component>
@endsection
