@extends('layouts.app')

@section('pageName', 'Events')
@section('title', $create == true ? 'New Event' : $event->name)

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    {{-- Header, if creating, open form for page, close at end of page --}}
    @if($create == true)
        {!! Form::open(
            array(
              'id' => 'eventForm',
              'url' => $event == null ? 'events/create/new' : 'event/'.$event->urlname.'/update',
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
            <div class="col-lg-4 col-sm-10 offset-lg-4">
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
                    <h3 id="event-name-heading">{{ ucfirst($event->UCName) }}</h3>
                    <small id="like-caption">Likes: {{ $event->likes }}</small>
                    <div class="full-padding">
                        @if($owner == true)
                            <button type="submit" id="edit-event-button" class="btn btn-outline-info"
                                    onclick="location.href='{{ url('/event/'.$event->urlname.'/edit') }}'">Edit
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
    <div class="row col-lg-10 offset-lg-2">
        <div class="col-lg-6">
            <h1>Description</h1>

            {{-- If creating, display textarea for description, else display description --}}
            @if($create == true)
                <script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
                {{ Form::textarea('description', $event == null ? '' : $event->description , ['id'=> 'description']) }}
            @else
                <div id="event-description">
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
            <h3>Contact</h3>
            <div>
                @if($create == true)
                    {{ Form::text('contact', $event == null ? Auth::user()->email : $event->contact, ['required' =>
                    'required',
                    'class' => 'form-control', 'placeholder' => 'Contact details for the event']) }}
                @else

                    @if($owner !== true)
                        <a id="mailto" href="mailto:{{ $event->contact }}"
                           role="button">{{ $event->user->userName }}</a>
                    @else
                        {{ $event->user->userName }}
                    @endif
                @endif
            </div>
            <h3>When</h3>
            <div>
                @if($create == true)
                    <div class="input-group">
                        {{ Form::date('date', $event == null ? null : explode(' ', $event->time)[0],
                        ['required' => 'required', 'class' => 'form-control']) }}
                        <span class="input-group-append">
                            {{ Form::time('time', $event == null ? null : explode(' ', $event->time)[1],
                            ['required' => 'required', 'class' => 'form-control']) }}
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
        @if($create == true)
            <div class="col-lg-10">
                <h2>Related Events</h2>
                <div class="form-group input-group col-lg-6 offset-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">Search For:</span>
                    </div>
                    <input type="text" id="filter-search" class="form-control"
                           onkeyup="filter()">
                </div>
            </div>
            <div id="related-events" class="card col-lg-8 offset-1">
                <div id="relatedContainer" class="card-body row">
                    @foreach($eventList as $relevent)
                        @if($event == null)
                            <div class="col-lg-6">
                                {{ Form::checkbox('related_events[]', $relevent->id,
                                $event == null ? null :
                                $event->RelatedEvents->contains($relevent) == true ? 'true' : null,
                                ['id' => $relevent->urlname]) }}

                                {{ Form::label($relevent->urlname, $relevent->name) }}
                            </div>
                        @elseif($event->id != $relevent->id)
                            <div class="col-lg-6">
                                {{ Form::checkbox('related_events[]', $relevent->id,
                                $event == null ? null :
                                $event->RelatedEvents->contains($relevent) == true ? 'true' : null,
                                ['id' => $relevent->urlname]) }}

                                {{ Form::label($relevent->urlname, $relevent->name) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <script>
                function filter() {
                    // Declare variables
                    let input, filter, relatedContainer, checkBoxDivs, label, i;
                    input = document.getElementById('filter-search');
                    filter = input.value.toUpperCase();
                    relatedContainer = document.getElementById("relatedContainer");
                    checkBoxDivs = relatedContainer.getElementsByTagName('div');

                    // Loop through all list items, and hide those who don't match the search query
                    for (i = 0; i < checkBoxDivs.length; i++) {
                        label = checkBoxDivs[i].getElementsByTagName("label")[0];
                        if (label.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            checkBoxDivs[i].style.display = "";
                            console.log("here");
                        } else {
                            checkBoxDivs[i].style.display = "none";
                        }
                    }
                }
            </script>
        @endif
    </div>
    @if($create == false && $event->relatedEvents->first())
        <hr/>
        <h2>Related Events</h2>
        @include('components.eventList', array('events' => $event->relatedEvents, 'includeHeading' => false))
    @endif

    {{-- If creating, show submit button, and close form --}}
    @if($create == true)
        <div class="text-center full-padding">
            <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                {{ $event == null ? "Create Event!" : "Update Event"}}
            </button>
        </div>
        {!! Form::close() !!}
    @elseif($owner == true)
        <div class="text-center full-padding">
            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModal">
                Delete Event
            </button>
        </div>
        <div class="fade modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Event?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Deleting an event will totally remove it from the site, all likes for this event will be
                        removed.
                        Are you sure you want to do this?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">I don't want this!
                        </button>
                        <a href="{{ url('events/delete/'.$event->urlname) }}">
                            <button type="button" class="btn btn-outline-danger">Delete</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Script for images --}}
    @if($create == true)
        <script>
            $(function () {
                console.log($('#picture').val());
            });

            //Setup ckEditor for description textarea
            CKEDITOR.replace('description', {
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

                console.log($('#picture').val());

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

                let likedEvents = [];
                let loggedin = {{ json_encode(Auth::check()) }};
                let liked = false;

                //check if user is logged in
                if (loggedin === false) {
                    //load liked events
                    likedEvents = JSON.parse(localStorage.getItem("liked-events"));
                    if (likedEvents === null) {
                        likedEvents = [];
                    }

                    liked = likedEvents.includes({{ $event->id }});
                } else {
                    liked = {{ json_encode($liked) }};
                }

                if (liked === true) {
                    likeButton.removeClass('btn-outline-primary');
                    likeButton.addClass('btn-success');
                    likeButton.html('Liked!');
                }

                //When like button is clicked, fire event
                likeButton.click(function () {
                    let shouldLike;
                    //if not logged in, use likeEvents ls
                    if (loggedin === false) {
                        shouldLike = true;
                        if (likedEvents.includes({{ $event->id }})) {
                            shouldLike = false;
                        }
                    } else {
                        //use $liked from controller
                        shouldLike = !liked;
                    }

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: 'POST',
                        url: '{{ url('/events/like') }}',
                        data: {id: '{{ $event->id }}', like: shouldLike.toString()},
                        success: function (response) {
                            liked = !liked;

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
                            //If a guest, store like data locally
                            if (loggedin === false) {
                                localStorage.setItem("liked-events", JSON.stringify(likedEvents))
                            }
                        }
                    });
                });
            });
            @endif
        </script>
    @endif
@endsection