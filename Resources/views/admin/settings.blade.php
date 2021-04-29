@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Settings',
    ])

    <settings-component :current-settings="{{ json_encode($settings) }}"></settings-component>
@endsection
