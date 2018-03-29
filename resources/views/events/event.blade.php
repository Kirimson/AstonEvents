@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')

    @if($create == true)
        {!! Form::open(
            array(
                'url' => 'events/create/new',
                'class' => 'form-horizontal',
                'files' => true)) !!}
    @endif

    <div class="row">
        <div id="event-image-container">
            @if($create == true)
                <input type="file" name="picture" id="picture" class="hidden-input" accept=".png,.jpg,.bmp,.svg"/>

                <label for="picture">
                    @endif
                    <img id="event-image"
                         src="{{ $create == true ? asset('img/events/default/default.svg') : $event->picture == null ?
                         asset('img/events/default/default.svg') : asset($event->picture) }}"
                         alt=""/>
                    @if($create == true)
                </label>

            @endif
            @if($create == true)
                <div class="col-lg-4 offset-4">
                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                           placeholder="Name of the event" name="name" id="name" type="text" required>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            @else
                <h3 id="event-name-heading">{{ $event->name }}</h3>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 offset-1">
            <h1>Description</h1>

            @if($create == true)
                {{ Form::textarea('description', null, ['id'=> 'description', 'size' => '50x9',
                'required' => 'required', 'class' => 'form-control', 'placeholder' => 'Description of the event']) }}
            @else
                <div>
                    {{ $event->description }}
                </div>
            @endif

        </div>
        <div class="col-lg-4">
            <h1>Details</h1>

            <h3>Category</h3>
            <div>
                {{ $create == true ? Form::select('category', array('sport' => 'Sport', 'culture' => 'Culture',
                'other' => 'Other'), 'other', ['required' => 'required', 'class' => 'form-control']) : $event->category }}
            </div>

            <h3>Organiser</h3>
            <div>
                {{ $create == true ? Auth::user()->name : $event->user->name }}
            </div>
            <div>
                {{ $create == true ? Form::text('contact', Auth::user()->email, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : $event->contact }}
            </div>
            <h3>Venue</h3>
            <div>
                {{ $create == true ? Form::text('venue', null, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : $event->venue }}
            </div>

        </div>
    </div>

    @if($create == true)
        <div id="event-image-container">
            <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                {{ __('Create Event!') }}
            </button>
        </div>
        {!! Form::close() !!}
    @endif

    {{-- Script for images --}}
    @if($create == true)
        <script>

            $(function () {
                $('#event-name-heading').html($('#name').val())
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#event-image').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#picture').change(function (e) {

                let files = e.currentTarget.files;

                let fileSize = ((files[0].size / 1024) / 1024).toFixed(4);


                let acceptedTypes = ["png", "jpg", "bmp", "svg"];
                let fileExtension = $(this).val().split('.').pop();

                console.log(fileExtension);

                console.log(fileSize);

                //if smaller than max image size
                if (fileSize <= 2 && !isNaN(fileSize) && (jQuery.inArray(fileExtension, acceptedTypes) !== -1)) {
                    $('#createSubmitButton').prop('disabled', false);
                    readURL(this);
                } else { //clear the image preview section
                    $('#picture').val('');
                    $('#event-image').attr({
                        src: '{!!asset('img/events/default/default.svg')!!}',
                        alt: 'File size is larger than 2MB'
                    });
                }
            });

            $('#name').on('input', function (e) {
                console.log("here");
                $('#event-name-heading').html($('#name').val())
            })
        </script>
    @endif
@endsection