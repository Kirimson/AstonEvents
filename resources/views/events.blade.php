@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <h1>Events</h1>

    {!! Form::open(array('url' => 'event/', 'class' => 'form')) !!}

    <div class="form-group">
        {{ Form::label('Event ID') }}
        {{ Form::text('id', null) }}
    </div>

    <div class="form-group">
        {!! Form::submit('Find Event') !!}
    </div>

    {!! Form::close() !!}

    @include('components.eventList', array('events' => $events))

@endsection