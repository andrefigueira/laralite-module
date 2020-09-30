@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Dashboard',
    ])

    <dashboard-component></dashboard-component>
@endsection
