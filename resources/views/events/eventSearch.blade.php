@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <h1>Events</h1>
    @if(Request::get('preset') != true)
        {!! Form::open(array('url' => 'events/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET')) !!}

        <h2>Search</h2>

        {{-- Search for --}}
        <div class="form-group row col-lg-4 offset-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="">Search For:</span>
                </div>
                {{ Form::select('atr', array('name' => 'Name', 'category' => 'Category',
                'organiser_id' => 'Organiser', 'time' => 'When', 'venue' => 'Venue'), 'name',
                ['class' => 'form-control', 'id' => 'form-atr']) }}
            </div>
        </div>

        {{-- What value to find --}}
        <div class="form-group row col-lg-4 offset-4" id="value-field">
            {{ Form::text('search', null, ['class' => 'form-control ', 'id' => 'search-textbox']) }}
        </div>

        <div id="event-image-container">
            <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                Find events
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
    {{ $events->links() }}

    {{-- Disables any inputs that are empty, so URL doesn't get clogged up with empty GET parameters --}}
    <script>

        $(function () {
            updateValueField($('#search-textbox').val());
        });

        $('#event-search-form').submit(function () {
            $('#event-search-form :input').each(function () {
                if (!$(this).val()) {
                    $(this).prop('disabled', true);
                }
            });
        });

        let formAtr = $('#form-atr');
        let valueInput = $('#value-field');

        formAtr.change(function (e) {
            updateValueField('');
        });

        function updateValueField(textFieldText) {
            switch (formAtr.val()) {
                case 'category':
                    valueInput.html('{{ Form::select('search', array('sport' => 'Sport', 'culture' => 'Culture',
                    'other' => 'Other'), 'other', ['class' => 'form-control']) }}');
                    break;
                case 'organiser_id':
                    valueInput.html('{{ Form::select('search', $users, '1', ['class' => 'form-control']) }}');
                    break;
                case 'time':
                    valueInput.html('{{ Form::date('search', $users, ['class' => 'form-control']) }}');
                    break;
                default:
                    valueInput.html('<input class="form-control" id="search-textbox" name="search" type="text" value="' + textFieldText + '">');
                    break;
            }
        }

    </script>
@endsection