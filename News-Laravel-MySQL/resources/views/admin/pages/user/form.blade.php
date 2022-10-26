@extends('admin.main')

@php

@endphp

@section('content')
    @include('admin.templates.page_header', ['pageIndex' => false])
    @include('admin.templates.error')

    @if(@$item['id'])
        <div class="row">
            @include('admin.pages.user.form_edit')
            @include('admin.pages.user.form_change_password')
            @include('admin.pages.user.form_change_level')
        </div>
    @else
        @include('admin.pages.user.form_add')
    @endif

@endsection
