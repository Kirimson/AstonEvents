@extends('layouts.app')

@section('title', 'Home')
@section('pageName', 'Home')

@section('content')
    <h1>Welcome to Aston Events</h1>
    <h2>Top Rated Events</h2>
    @include('components.eventList', array('events' => $topEvents))
    <h2>Newest Events</h2>
    @include('components.eventList', array('events' => $upcomingEvents))
    <h2>About</h2>
    <div class="col-lg-8 offset-2">
        <p>Aston Events is a site dedicated to university organised events.</p>
        <p>It is made to be a one-stop-shop for
            all Aston University events. Each event has it's own page with a description organiser details, venue and
            date. Each event falls into one of three categories, Sport, Culver and Other. Look around and see if there
            is anything that interests you!</p>
        <h3>Interacting With Events</h3>
        <p>On each event page, you can contact the organiser, or show your interest in the event by clicking the
            like button. The 5 most liked events will be shown on the Front Page.</p>
        <h3>Finding Events</h3>
        <p>Use the search feature to find new events, search by name, venue, and organiser. Events can then be sorted
            by their name, amount of likes and time of the event.</p>
        @if(!Auth::check())
            <h3>Registering as an Organiser</h3>
            <p>Want to contribute to Aston Events and become an Organiser? Great! As an organiser, you can create
                multiple
                events, and access your own dashboard, where your events can be managed and updated. Either click the
                register button in the login page, or click the button below to register as an organiser!</p>

            <div class="row">
                <a class="btn btn-outline-success offset-5" href="{{ url('/register') }}" role="button">Create
                    an
                    Account</a>
            </div>
        @endif
    </div>
@endsection