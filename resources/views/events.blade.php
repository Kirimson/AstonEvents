@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <h1>Events</h1>
    @if(Request::get('preset') != true)
        {!! Form::open(array('url' => 'events/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET', 'onsubmit' => 'disablefields();')) !!}

        {{-- Search for --}}
        <div class="form-group row">
            <div class="col-lg-2 offset-md-3 text-md-right">
                {{ Form::label('Search for:') }}
            </div>
            <div class="input-group col-lg-3">
                {{ Form::select('atr', array('name' => 'Name', 'category' => 'Category',
                        'organiser_id' => 'Organiser', 'time' => 'When', 'venue' => 'Venue'), 'name', [
                        'class' => 'form-control', 'id' => 'form-atr']) }}
            </div>
        </div>

        {{-- What value to find --}}
        <div class="form-group row">
            <div class="col-lg-2 offset-md-3 text-md-right">
                {{ Form::label('Search Query:') }}
            </div>
            <div id="value-field" class="col-lg-3">
                {{ Form::text('search', null, ['required' => 'required',
                'class' => 'form-control ', 'id' => 'search-textbox']) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-2 offset-md-3 text-md-right">
                {{ Form::label('Sort By:') }}
            </div>
            <div class="input-group col-lg-3">
                {{ Form::select('orderBy', array('name' => 'Name', 'category' => 'Category',
                    'organiser_id' => 'Organiser', 'time' => 'When', 'venue' => 'Venue', 'likes' => 'Likes'), 'name', [
                    'class' => 'form-control', 'id' => 'form-atr']) }}
                <span class="input-group-addon">
                {{ Form::select('order', array(0 => 'Ascending', 1 => 'Descending'), 'name', [
                    'class' => 'form-control', 'id' => 'form-atr']) }}
            </span>
            </div>
        </div>

        <div id="event-image-container">
            <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                Find event!
            </button>
        </div>

        {!! Form::close() !!}
    @else
        @switch(Request::get('orderBy'))
            @case('likes')
            <h2>Most liked events</h2>
            @break
            @case('time')
            <h2>Upcoming events</h2>
            @break
        @endswitch
    @endif
    @include('components.eventList', array('events' => $events))

    {{-- Disables any inputs that are empty, so URL doesn't get clogged up with empty GET parameters --}}
    <script>
        $(function () {
            oldSearch = $('#search-textbox').val();
            updateValueField();
            $('#search-textbox').on('input', function () {
                oldSearch = $('#search-textbox').val();
            });
        });

        function disablefields() {
            $('#event-search-form :input').each(function () {
                if (!$(this).val()) {
                    $(this).prop('disabled', true);
                }
            });
        }

        let formAtr = $('#form-atr');
        let valueinput = $('#value-field');
        let oldSearch = '';

        formAtr.change(function (e) {
            updateValueField();
        });

        function updateValueField() {
            if (formAtr.val() === 'category') {
                valueinput.html('{{ Form::select('search', array('sport' => 'Sport', 'culture' => 'Culture',
                'other' => 'Other'), 'other', ['required' => 'required',
                'class' => 'form-control']) }}')
            } else if (formAtr.val() === 'organiser_id') {
                valueinput.html('{{ Form::select('search', $users, '1', ['required' => 'required',
                'class' => 'form-control']) }}');
            } else {
                valueinput.html('<input class="form-control" id="search-textbox" name="search" type="text" value="' + oldSearch + '">');
            }
        }

    </script>
@endsection