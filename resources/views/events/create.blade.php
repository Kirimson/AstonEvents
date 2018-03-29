@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')
    <div>
        {!! Form::open(
        array(
            'url' => 'events/create/new',
            'class' => 'form-horizontal',
            'novalidate' => 'novalidate',
            'files' => true)) !!}

        <fieldset>
            {{--Name--}}
            <div class="form-group row">
                {!! Form::label('name', 'Event Name', ['class' => 'col-lg-2 col-form-label text-md-right']) !!}
                <div class="col-lg-10">
                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                           placeholder="Name of the event" name="name" id="name" type="text">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            {{--Description--}}
            <div class="form-group row">
                {!! Form::label('description', 'Description', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                <div class="col-lg-10">
                    {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Description of the event']) }}
                </div>
            </div>

            {{-- Category --}}
            <div class="form-group row">
                {!! Form::label('a', 'Category', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                <div class="col-lg-10">
                    {{ Form::select('category', array('sport' => 'Sport', 'culture' => 'Culture', 'other' => 'Other'), 'other',
                    ['class' => 'form-control']) }}
                </div>
            </div>

            {{--Image--}}
            <div class="form-group row">
                {!! Form::label('picture', 'Event Image', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                {!! Form::file('picture', null, ['class' => 'form-control-file']) !!}
            </div>

            {{--Organiser Should be a hidden field, using your logged in user ID--}}
            {{ Form::hidden('organiser_id', Auth::user()->id) }}

            {{--Contact--}}
            <div class="form-group row">
                {!! Form::label('contact', 'Contact Details', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                <div class="col-lg-10">
                    {{ Form::text('contact', null, ['class' => 'form-control', 'placeholder' => 'Contact details for the event']) }}
                </div>
            </div>

            {{--Venue--}}
            <div class="form-group row">
                {!! Form::label('venue', 'Venue', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                <div class="col-lg-10">
                    {{ Form::text('venue', null, ['class' => 'form-control', 'placeholder' => 'Venue event will take place at']) }}
                </div>
            </div>

            {{--Create Button--}}
            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        {{ __('Create Event!') }}
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </fieldset>
    </div>
@endsection