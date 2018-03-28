@extends('layouts.app')

@section('title', 'Event')
@section('pageName', 'Events')

@section('content')
    @if($event != null)
        <ul>
            <li>name: {{ $event->name }}</li>
            <li>description: {{ $event->description }}</li>
            <li>time made: {{ $event->time }}</li>
            <li>organiser id: {{ $event->organiser_id }}</li>
            <li>contact: {{ $event->contact }}</li>
            <li>venue: {{ $event->venue }}</li>
            <li>{{ Html::image($event->picture) }}</li>
        </ul>
    @else
        <div>not found</div>
    @endif
@endsection