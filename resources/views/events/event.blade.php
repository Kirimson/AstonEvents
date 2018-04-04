@extends('layouts.app')

@section('pageName', 'Events')
@section('title', $create == true ? 'New Event' : $event->name)

@section('content')

    {{-- Header, if creating, open form for page, close at end of page --}}
    @if($create == true)
        {!! Form::open(
            array(
              'id' => 'eventForm',
              'url' => $event == null ? 'events/create/new' : 'event/'.rawurlencode($event->name).'/update',
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
                         src="{{ $event == null ? asset('img/events/default/default.svg') : $event->picture == null ?
                         asset('img/events/default/default.svg') : asset($event->picture) }}"
                         alt=""/>
                    @if($create == true)
                </label>
            @endif
            {{-- If creating, display name form, otherwise, display event name --}}
            <div class="col-lg-4 offset-4">
                @if($create == true)
                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                           placeholder="Name of the event" name="name" id="name" type="text"
                           value="{{ $event == null ? '' : $event->name }}" required>
                    {{-- If there are errors, display them, and mark form input as invalid--}}
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                @else
                    {{-- Like button --}}
                    <h3 id="event-name-heading">{{ ucfirst($event->name) }}</h3>
                    <small id="like-caption">Likes: {{ $event->likes }}</small>
                    <div class="full-padding">
                        @if($owner == true)
                            <button type="submit" id="edit-event-button" class="btn btn-outline-info"
                                    onclick="location.href='{{ url('/event/'.rawurlencode($event->name).'/edit') }}'">Edit
                            </button>
                        @else
                            <button type="submit" id="like-event-button" class="btn btn-outline-primary">Like</button>
                        @endif
                    </div>
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
                <script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
                {{ Form::textarea('description', $event == null ? '' : $event->description , ['id'=> 'description']) }}
            @else
                <div>
                    {!! ucfirst($event->description) !!}
                </div>
            @endif

        </div>
        <div class="col-lg-4">
            <h1>Details</h1>
            {{-- Show dropdown if creating, else show category --}}
            <h3>Category</h3>
            <div>
                {{ $create == true ? Form::select('category', array('sport' => 'Sport', 'culture' => 'Culture',
                'other' => 'Other'), $event == null ? 'other' : $event->category, ['required' => 'required',
                'class' => 'form-control']) : $event->UCCategory }}
            </div>

            {{-- Show your username if creating, else, organiser of event --}}
            <h3>Organiser</h3>
            <div>
                {{ $create == true ? ucfirst(Auth::user()->name) : ucfirst($event->user->name) }}
            </div>
            <div>
                {{ $create == true ? Form::text('contact', $event == null ? Auth::user()->email : $event->contact, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : $event->contact }}
            </div>
            <h3>When</h3>
            <div>
                @if($create == true)
                    <div class="input-group">
                        {{ Form::date('date', $event == null ? null : explode(' ', $event->time)[0], ['required' => 'required', 'class' => 'form-control']) }}
                        <span class="input-group-addon">
                            {{ Form::time('time', $event == null ? null : explode(' ', $event->time)[1], ['required' => 'required', 'class' => 'form-control']) }}
                        </span>
                    </div>
                @else
                    {{ $event->readableTime }}
                @endif
            </div>
            <h3>Where</h3>
            <div>
                {{ $create == true ? Form::text('venue', $event == null ? '' : $event->venue, ['required' => 'required',
                'class' => 'form-control', 'placeholder' => 'Contact details for the event']) : $event->UCVenue }}
            </div>

        </div>
    </div>

    {{-- If creating, show submit button, and close form --}}
    @if($create == true)
        <div id="event-image-container">
            <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                {{ $event == null ? "Create Event!" : "Update Event"}}
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

            //Setup ckEditor for description textarea
            CKEDITOR.replace('description',{
                contentsCss: '{{ asset('css/app.css') }}'
            });

            //Reads a given (fake)path of the uploaded image and reads the image, setting the img to the uploaded image
            function updateEventIcon(file) {
                if (file.files && file.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        $('#event-image').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(file.files[0]);
                }
            }

            $('#picture').change(function (e) {

                let files = e.currentTarget.files;
                let fileSize = ((files[0].size / 1024) / 1024).toFixed(4);
                let acceptedTypes = ["png", "jpg", "bmp", "svg"];
                let fileExtension = $(this).val().split('.').pop();

                //if smaller than max image size and a supported image
                if (fileSize <= 2 && !isNaN(fileSize) && (jQuery.inArray(fileExtension, acceptedTypes) !== -1)) {
                    updateEventIcon(this);
                } else { //clear the image preview section
                    $('#picture').val('');
                    $('#event-image').attr({
                        src: '{{ asset('img/events/default/default.svg') }}',
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
            {{-- If not owner, script for like button --}}
            @if($owner == false)
            $(function () {
                let likeButton = $('#like-event-button');
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
                    likeButton.removeClass('btn-outline-primary');
                    likeButton.addClass('btn-success');
                    likeButton.html('Liked!');
                }

                likeButton.click(function () {
                    let shouldLike = true;
                    if (likedEvents.includes({{ $event->id }})) {
                        shouldLike = false;
                    }
                    console.log(shouldLike);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: 'POST',
                        url: '{{ url('/events/like') }}',
                        data: {id: '{{ $event->id }}', like: shouldLike.toString()},
                        success: function (response) {
                            console.log(response);
                            $('#like-caption').html("Likes: " + response);
                            if (shouldLike === true) {
                                likeButton.removeClass('btn-outline-primary');
                                likeButton.addClass('btn-success');
                                likeButton.html('Liked!');
                                likedEvents.push({{ $event->id }});

                            } else {
                                likeButton.removeClass('btn-success');
                                likeButton.addClass('btn-outline-primary');
                                likeButton.html('Like');
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
            @endif
        </script>
    @endif
@endsection