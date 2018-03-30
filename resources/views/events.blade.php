@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <div><a href="{{ url('events/list') }}">List events</a> </div>
    <div><a href="{{ url('events/create') }}">Create event</a> </div>

    {!! Form::open(array('url' => 'events/', 'class' => 'form')) !!}

    <div class="form-group">
        {{ Form::label('Event ID') }}
        {{ Form::text('id', null) }}
    </div>

    <div class="form-group">
        {!! Form::submit('Find Event') !!}
    </div>

    {!! Form::close() !!}

@endsection