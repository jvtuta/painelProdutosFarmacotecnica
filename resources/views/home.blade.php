@extends('layout.app')

@section('content')
        <home-component action="{{ action([App\Http\Controllers\Home::class, 'data']) }}" csrf="{{ csrf_token() }}">
        </home-component>
@endsection