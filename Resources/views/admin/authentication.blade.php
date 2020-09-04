@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
         'title' => 'Authentication',
     ])

    <clients class="mb-3"></clients>

    <authorized-clients class="mb-3"></authorized-clients>

    <personal-access-tokens class="mb-3"></personal-access-tokens>
@endsection
