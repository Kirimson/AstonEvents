@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')

    {{-- Header, if creating, open form for page, close at end of page --}}
    @if($create == true)
        {!! Form::open(
            array(
                'url' => 'events/create/new',
                'class' => 'form-horizontal',
                'files' => true)) !!}
    @endif
    <div class="row">
        <div id="event-image-container">
            {{-- if creating a new event, create label and file input. otherwise, display event image --}}
            @if($create == true)
                <input type="file" name="picture" id="picture" class="hidden-input" accept=".png,.jpg,.bmp,.svg"/>

                <label for="picture">
                    @endif
                    {{-- if creating, display default. if not, display event image. if no image, fallback --}}
                    <img id="event-image"
                         src="{{ $create == true ? asset('img/events/default/default.svg') : $event->picture == null ?
                         asset('img/events/default/default.svg') : asset($event->picture) }}"
                         alt=""/>
                    @if($create == true)
                </label>
            @endif
            {{-- If creating, display name form, otherwise, display event name --}}
            <div class="col-lg-4 offset-4">
                @if($create == true)
                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                           placeholder="Name of the event" name="name" id="name" type="text" required>
                    {{-- If there are errors, display them, and mark form input as invalid--}}
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                @else
                    <h3 id="event-name-heading">{{ ucfirst($event->name) }}</h3>
                    <button type="submit" id="like-event-button" class="btn btn-outline-primary">Like</button>
                @endif
            </div>
        </div>
    </div>

    {{-- Start description/details section --}}
    <div class="row">
        <div class="col-lg-6 offset-1">
            <h1>Description</h1>

            {{-- If creating, display textarea for description, else display description --}}
            @if($create == true)
                {{ Form::textarea('description', null, ['id'=> 'description', 'size' => '50x9',
                'required' => 'required', 'class' => 'form-control', 'placeholder' => 'Description of the event']) }}
            @else
                <div>
                    {{ ucfirst($event->description) }}
                </div>
            @endif

        </div>
        <div class="col-lg-4">
            <h1>Details</h1>
            {{-- Show dropdown if creating, else show category --}}
            <h3>Category</h3>
            <div>
                {{ $create == true ? Form::select('category', array('sport' => 'Sport', 'culture' => 'Culture',
                'other' => 'Other'), 'other', ['required' => 'required', 'class' => 'form-control']) : ucfirst($event->category) }}
            </div>

            {{-- Show your username if creating, else, organiser of event --}}
            <h3>Organiser</h3>
            <div>
                {{ $create == true ? ucfirst(Auth::user()->name) : ucfirst($event->user->name) }}
            </div>
            <div>
                {{ $create == true ? Form::text('contact', Auth::user()->email, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : $event->contact }}
            </div>
            <h3>When</h3>
            <div>
                @if($create == true)
                    <div class="input-group">
                        {{ Form::date('date', null, ['required' => 'required', 'class' => 'form-control']) }}
                        <span class="input-group-addon">
                            {{ Form::time('time', null, ['required' => 'required', 'class' => 'form-control']) }}
                        </span>
                    </div>
                @else
                    {{ $event->time }}
                @endif
            </div>
            <h3>Where</h3>
            <div>
                {{ $create == true ? Form::text('venue', null, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : ucfirst($event->venue) }}
            </div>

        </div>
    </div>

    {{-- If creating, show submit button, and close form --}}
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
    @else
        <script>
            console.log("viewing");

            $(function () {
                //load liked events
                let likedEvents = [];
                try {
                    likedEvents = JSON.parse(localStorage.getItem("liked-events"));
                    let test = likedEvents[0];
                } catch (err) {
                    console.log(err.message);
                    likedEvents = [];
                }

                if (likedEvents.includes({{ $event->id }})) {
                    $('#like-event-button').removeClass('btn-outline-primary');
                    $('#like-event-button').addClass('btn-success');
                    $('#like-event-button').html('Liked!');
                }

                $('#like-event-button').click(function () {
                    let shouldLike = true;
                    if (likedEvents.includes({{ $event->id }})) {
                        shouldLike = false;
                    }
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: 'POST',
                        url: (shouldLike === false ? 'un' : '')+'like',
                        data: {id: '{{ $event->id }}'},
                        success: function (response) {
                            if (shouldLike === true) {
                                $('#like-event-button').removeClass('btn-outline-primary');
                                $('#like-event-button').addClass('btn-success');
                                $('#like-event-button').html('Liked!');
                                likedEvents.push({{ $event->id }});
                            } else {
                                $('#like-event-button').removeClass('btn-success');
                                $('#like-event-button').addClass('btn-outline-primary');
                                $('#like-event-button').html('Like');
                                let indexOf = likedEvents.indexOf({{ $event->id }});
                                if (indexOf > -1) {
                                    likedEvents.splice(indexOf, 1);
                                }
                            }
                            localStorage.setItem("liked-events", JSON.stringify(likedEvents))
                        }
                    });
                });
            });


        </script>
    @endif
@endsection