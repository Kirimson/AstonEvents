@extends('layouts.app')
@section('pageName', 'My Account')
@section('title', 'My Account')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h1>My Account</h1>
    <h3>Welcome to your page, {{ Auth::user()->name }}</h3>
    <h2>My Events</h2>
    {!! Form::open(array('url' => 'myAccount/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET')) !!}

    {{-- What name to find --}}
    <div class="col-lg-4 offset-lg-4">
        <div class="row">
            {{ Form::text('search', null, ['class' => 'form-control col-lg-8 col-sm-12', 'id' => 'search-textbox']) }}
            <button type="submit" class="btn btn-outline-primary col-lg-4">
                Search Events
            </button>
        </div>
    </div>

    {!! Form::close() !!}

    @include('components.eventList',  array('events' => $myEvents))
    {{ $myEvents->links("pagination::bootstrap-4") }}
@endsection
