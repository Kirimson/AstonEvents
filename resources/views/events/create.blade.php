
@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')
    {{--<form action=" {{ url('events/create/new') }}">--}}
        {{--<input type="text" name="name" placeholder="name">--}}
        {{--<input type="text" name="description" placeholder="description">--}}
        {{--<input type="file" name="picture">--}}
        {{--<input type="text" name="organiser_id" placeholder="organiser id">--}}
        {{--<input type="text" name="contact" placeholder="contact">--}}
        {{--<input type="text" name="venue" placeholder="venue">--}}
        {{--<input type="submit" onclick="" value="Create">--}}
    {{--</form>--}}

    {!! Form::open(
    array(
        'url' => 'events/create/new',
        'class' => 'form',
        'novalidate' => 'novalidate',
        'files' => true)) !!}

    <div class="form-group">
        {!! Form::label('Event Name') !!}
        {!! Form::text('name', null, array('placeholder'=>'Chess Board')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Description') !!}
        {!! Form::text('description', null, array('placeholder'=>'1234')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Image') !!}
        {!! Form::file('picture', null) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Organiser id') !!}
        {!! Form::text('organiser_id', null) !!}
    </div>

    <div class="form-group">
        {!! Form::label('contact') !!}
        {!! Form::text('contact', null) !!}
    </div>

    <div class="form-group">
        {!! Form::label('venue') !!}
        {!! Form::text('venue', null) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Create Event!') !!}
    </div>
    {!! Form::close() !!}

@endsection