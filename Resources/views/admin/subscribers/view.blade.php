@extends('laralite::admin.layout')

@section('content')
    <subscriber-view-component :customer-subscription="{{ $customerSubscription ?? '{}' }}"></subscriber-view-component>
@endsection
