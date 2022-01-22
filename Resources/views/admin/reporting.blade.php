@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Reporting'
    ])

    <reporting-component></reporting-component>

@endsection

