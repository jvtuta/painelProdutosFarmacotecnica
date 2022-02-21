@extends('layout.app')

@section('content')
    <home-component action="{{ action([App\Http\Controllers\Home::class, 'data']) }}">
    </home-component>
@endsection
