@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Variables',
    ])

    <variables-component></variables-component>
@endsection
