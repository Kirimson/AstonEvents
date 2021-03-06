@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <h1>Events</h1>
    @if(Request::get('preset') != true)
        {!! Form::open(array('url' => 'events/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET')) !!}

        {{-- Search for --}}
        <div class="form-group row">
            <div class="col-lg-2 offset-md-3 text-md-right">
                {{ Form::label('atr', 'Search for:') }}
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
                {{ Form::label('search', 'Search Query:', array('id' => 'search-label')) }}
            </div>
            <div id="value-field" class="col-lg-3">
                {{ Form::text('search', null, ['class' => 'form-control ', 'id' => 'search-textbox']) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-2 offset-md-3 text-md-right">
                {{ Form::label('Sort By:') }}
            </div>
            <div class="input-group col-lg-3">
                {{ Form::select('orderBy', array('name' => 'Name', 'category' => 'Category',
                    'organiser_id' => 'Organiser', 'time' => 'When', 'venue' => 'Venue', 'likes' => 'Likes',
                    'created_at' => 'Recently Made'), 'name', ['class' => 'form-control', 'id' => 'form-atr']) }}
                <span class="input-group-addon">
                {{ Form::select('order', array('ascending' => 'Ascending', 'descending' => 'Descending'), 'name', [
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
            updateValueField($('#search-textbox').val());
        });

        $('#event-search-form').submit(function(){
            $('#event-search-form :input').each(function () {
                if (!$(this).val()) {
                    $(this).prop('disabled', true);
                }
            });
        });

        let formAtr = $('#form-atr');
        let valueinput = $('#value-field');

        formAtr.change(function (e) {
            updateValueField('');
        });

        function updateValueField(textFieldText) {
            switch (formAtr.val()) {
                case 'category':
                    valueinput.html('{{ Form::select('search', array('sport' => 'Sport', 'culture' => 'Culture',
                    'other' => 'Other'), 'other', ['class' => 'form-control']) }}');
                    break;
                case 'organiser_id':
                    valueinput.html('{{ Form::select('search', $users, '1', ['class' => 'form-control']) }}');
                    break;
                case 'time':
                    valueinput.html('{{ Form::date('search', $users, ['class' => 'form-control']) }}');
                    break;
                default:
                    valueinput.html('<input class="form-control" id="search-textbox" name="search" type="text" value="'+textFieldText+'">');
                    break;
            }
        }

    </script>
@endsection