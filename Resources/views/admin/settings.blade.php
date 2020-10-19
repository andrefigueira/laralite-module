@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Settings',
    ])

    <settings-component></settings-component>
@endsection
