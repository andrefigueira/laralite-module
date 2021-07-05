@extends('laralite::admin/layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
         'title' => 'Authentication',
     ])
    <div class="row">
        <div class="col-md-12">
            <clients class="mb-3"></clients>
        </div>
        <div class="col-md-12">
            <authorized-clients class="mb-3"></authorized-clients>
        </div>
        <div class="col-md-12">
            <personal-access-tokens class="mb-3"></personal-access-tokens>
        </div>
    </div>
@endsection
