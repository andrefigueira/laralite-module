@extends('admin.layout')

@section('content')
    <pages-form type="{{ $type ?? null }}" :page="{{ $page ?? '{}' }}"></pages-form>
@endsection
