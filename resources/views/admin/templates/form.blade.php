@extends('admin.layout')

@section('content')
    <templates-form type="{{ $type ?? null }}" :template="{{ $template ?? '{}' }}"></templates-form>
@endsection
