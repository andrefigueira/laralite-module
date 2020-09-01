@extends('laralite::admin.layout')

@section('content')
    @include('laralite::admin.partials.admin-title-section', [
         'title' => 'Component Management',
         'buttons' => [
             [
                 'label' => 'Scan for new',
                 'href' => '#'
             ],
         ],
     ])

    <div class="page-section">
        <component-list-component></component-list-component>
    </div><!-- End page section -->
@endsection
