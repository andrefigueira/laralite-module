@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
        'title' => 'Data Import',
    ])

    <data-import-component></data-import-component>
@endsection
