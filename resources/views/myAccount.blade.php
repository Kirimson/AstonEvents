@extends('layouts.app')
@section('pageName', 'My Account')
@section('title', 'My Account')

@section('content')
    <h1>My Account</h1>

    <h3>Welcome to your page, {{ Auth::user()->name }}</h3>
    <h2>My Events</h2>

    {!! Form::open(array('url' => 'myAccount/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET')) !!}

    {{-- What name to find --}}
    <div class="form-group row col-lg-6 offset-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Search For:</span>
            </div>
            {{ Form::text('search', null, ['class' => 'form-control ', 'id' => 'search-textbox']) }}
            <div class="input-group-append">
                <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                    Find events
                </button>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

    @include('components.eventList',  array('events' => $myEvents))
    {{ $myEvents->links("pagination::bootstrap-4") }}
@endsection
