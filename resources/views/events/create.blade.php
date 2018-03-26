@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')
    <div class="well">
        {!! Form::open(
        array(
            'url' => 'events/create/new',
            'class' => 'form-horizontal',
            'novalidate' => 'novalidate',
            'files' => true)) !!}

        <fieldset>
            {{--Name--}}
            <div class="form-group">
                {!! Form::label('name', 'Event Name:', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-12">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Event Name']) }}
                </div>
            </div>

            {{--Description--}}
            <div class="form-group">
                {!! Form::label('description', 'Description:', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-12">
                    {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                </div>
            </div>

            {{--Image--}}
            <div class="form-group">
                {!! Form::label('picture', 'Event Image:', ['class' => 'col-lg-2 control-label']) !!}
                {!! Form::file('picture', null, ['class' => 'form-control-file']) !!}
            </div>

            {{--Organiser--}}
            <div class="form-group">
                {!! Form::label('organiser', 'Organiser:', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-12">
                    {{ Form::text('organiser_id', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                </div>
            </div>

            {{--Contact--}}
            <div class="form-group">
                {!! Form::label('contact', 'Contact Details:', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-12">
                    {{ Form::text('contact', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                </div>
            </div>

            {{--Venue--}}
            <div class="form-group">
                {!! Form::label('venue', 'Venue', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-12">
                    {{ Form::text('venue', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                </div>
            </div>

            {{--Create Button--}}
            <div class="form-group">
                {!! Form::submit('Create Event!', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </fieldset>
    </div>
@endsection