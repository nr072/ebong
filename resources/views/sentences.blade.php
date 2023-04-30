@extends('layouts.app')

@section('tab-title')
    Sentences
@endsection

@section('main')

    <livewire:sentence-bulk-adder />

    <livewire:sentence-adder />

    <livewire:sentence-editor />

    <livewire:sentence-index />

@endsection
