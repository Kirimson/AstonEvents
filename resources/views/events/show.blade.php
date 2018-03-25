@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')
@section('test', 'hello')

@section('content')
<ul>
    <li>description: {{ $event->description }}</li>
    <li>time made: {{ $event->time }}</li>
    <li>organiser id: {{ $event->organiser_id }}</li>
    <li>contact: {{ $event->contact }}</li>
    <li>venue: {{ $event->venue }}</li>
</ul>
@endsection