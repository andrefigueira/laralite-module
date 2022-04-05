@extends('laralite::admin.layout')

@section('content')
    <subscriptions-form-component type="{{ $type ?? null }}" :subscription="{{ $subscription ?? '{}' }}"></subscriptions-form-component>
@endsection
