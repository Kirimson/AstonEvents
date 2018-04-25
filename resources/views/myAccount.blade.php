@extends('layouts.app')
@section('pageName', 'My Account')
@section('title', 'My Account')

@section('content')
    {{--Alert for deleting an event--}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h1>My Account</h1>
    <h3>Welcome to your page, {{ Auth::user()->name }}</h3>
    <h2>My Events</h2>
    {{--Search form for looking through your events--}}
    {!! Form::open(array('url' => 'myAccount/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET')) !!}
    <div class="col-lg-4 offset-lg-4">
        <div class="row">
            {{ Form::text('search', null, ['class' => 'form-control col-lg-8 col-sm-12', 'id' => 'search-textbox']) }}
            <button type="submit" class="btn btn-outline-primary col-lg-4">
                Search Events
            </button>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- Show users events --}}
    @include('components.eventList',  array('events' => $myEvents, 'includeHeading' => false))
    {{ $myEvents->links("pagination::bootstrap-4") }}
@endsection
