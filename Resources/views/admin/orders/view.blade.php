@extends('laralite::admin.layout')

@section('content')
    <orders-view-component :order="{{ $order[0] ?? '{}' }}"></orders-view-component>
@endsection
