@extends('laralite::admin.layout')

@section('content')
    <customers-view-component :customer="{{ $customer[0] ?? '{}' }}"></customers-view-component>
@endsection
