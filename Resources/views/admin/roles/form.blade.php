@extends('laralite::admin.layout')

@section('content')
    <roles-form-component type="{{ $type ?? null }}" :role="{{ $role ?? '{}' }}" :rolepermissions="{{ $rolePermissions ?? '[]' }}"></roles-form-component>
@endsection
