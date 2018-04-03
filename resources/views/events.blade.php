@extends('layouts.app')

@section('title', 'Events')
@section('pageName', 'Events')

@section('content')

    <h1>Events</h1>

    {!! Form::open(array('url' => 'events/','id' => 'event-search-form', 'class' => 'form', 'method' => 'GET', 'onsubmit' => 'disablefields();')) !!}

    {{-- Search for --}}
    <div class="form-group row">
        <div class="col-lg-2 offset-3 text-md-right">
            {{ Form::label('Search for:') }}
        </div>
        {{ Form::select('atr', array('name' => 'Name', 'category' => 'Category',
                'organiser_id' => 'Organiser', 'time' => 'When', 'venue' => 'Venue'), 'name', [
                'class' => 'form-control col-3', 'id' => 'form-atr']) }}
    </div>
    {{-- What value to find --}}
    <div class="form-group row">
        <div class="col-lg-2 offset-3 text-md-right">
            {{ Form::label('What') }}
        </div>
        <div id="value-field" class="col-lg-3">
            {{ Form::text('search', 'hello', ['required' => 'required',
                    'class' => 'form-control ', 'id' => 'search-textbox']) }}
        </div>
    </div>

    {{--<div class="form-group row">--}}
    {{--<div class="col-3 offset-2 col-form-label text-md-right">--}}
    {{--{{ Form::label('What') }}--}}
    {{--</div>--}}
    {{--{{ Form::text('search', null) }}--}}
    {{--</div>--}}
    {{--<div class="form-group row">--}}
    {{--<div class="col-3 offset-2 col-form-label text-md-right">--}}
    {{--{{ Form::label('Order By') }}--}}
    {{--</div>--}}
    {{--{{ Form::text('orderBy', null, array('class' => 'form-control col-lg-3')) }}--}}
    {{--</div>--}}
    {{--<div class="form-group row">--}}
    {{--<div class="col-3 offset-2 col-form-label text-md-right">--}}
    {{--{{ Form::label('Order Type') }}--}}
    {{--</div>--}}
    {{--{{ Form::text('order', null) }}--}}
    {{--</div>--}}

    <div class="form-group row">
        {!! Form::submit('Find Event') !!}
    </div>

    {!! Form::close() !!}

    @include('components.eventList', array('events' => $events))

    {{-- Disables any inputs that are empty, so URL doesn't get clogged up with empty GET parameters --}}
    <script>
        $(function () {
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