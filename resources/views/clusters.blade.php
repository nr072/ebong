@extends('layouts.app')

@section('tab-title')
    Clusters
@endsection

@section('main')

    <livewire:cluster-adder />

    <livewire:cluster-index />

    <livewire:cluster-editor />

@endsection
