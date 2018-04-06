@extends('layouts.app')
@section('pageName', 'My Account')
@section('title', 'My Account')

@section('content')
    <h1>My Account</h1>

    <h3>Welcome to your page, {{ Auth::user()->name }}</h3>
    <h2>My Events</h2>

    @include('components.eventList',  array('events' => $myEvents))

@endsection
