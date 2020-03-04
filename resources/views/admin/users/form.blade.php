@extends('admin.layout')

@section('content')
    <users-form type="{{ $type ?? null }}" :user="{{ $user ?? '{}' }}"></users-form>
@endsection
