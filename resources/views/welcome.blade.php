@extends('layouts.app')

@section('tab-title')
    cat
@endsection

@section('more-css')
    a.temp-nav {
        margin: 1rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #333;
    }
@endsection


@section('main')

    <div style="padding: 2em 0;">
        <a class="temp-nav" href="{{ route('words-page') }}">words</a>
        <a class="temp-nav" href="{{ route('groups-page') }}">groups</a>
        <a class="temp-nav" href="{{ route('sentence-index-page') }}">sentence index</a>
        <a class="temp-nav" href="{{ route('sentence-add-page') }}">add sentences</a>
    </div>

@endsection
