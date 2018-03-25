
@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')
    <div><a href="{{ url('events/list') }}">List events</a> </div>
    <div><a href="{{ url('events/create') }}">Create event</a> </div>

    <form action=" {{ url('events/show') }}">
        <input type="text" name="id">
        <input type="submit" onclick="">
    </form>

@endsection