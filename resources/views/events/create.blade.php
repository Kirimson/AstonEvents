@extends('layouts.app')

@section('title', 'Create Event')
@section('pageName', 'Events')

@section('content')

    <div class="row">
        <div class="col-lg-9">
            {!! Form::open(
            array(
                'url' => 'events/create/new',
                'class' => 'form-horizontal',
                'files' => true)) !!}

            <fieldset>
                {{--Name--}}
                <div class="form-group row">
                    {!! Form::label('', 'Event Name', ['class' => 'col-lg-2 col-form-label text-md-right']) !!}
                    <div class="col-lg-10">
                        <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                               placeholder="Name of the event" name="name" id="name" type="text" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                {{--Description--}}
                <div class="form-group row">
                    {!! Form::label('', 'Description', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                    <div class="col-lg-10">
                        {{ Form::text('description', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Description of the event']) }}
                    </div>
                </div>

                {{-- Category --}}
                <div class="form-group row">
                    {!! Form::label('', 'Category', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                    <div class="col-lg-10">
                        {{ Form::select('category', array('sport' => 'Sport', 'culture' => 'Culture', 'other' => 'Other'), 'other',
                        ['required' => 'required', 'class' => 'form-control']) }}
                    </div>
                </div>

                {{--Image--}}
                <div class="form-group row">
                    {!! Form::label('', 'Event Image', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                    <input type="file" name="picture" id="picture" class="hidden-input"/>
                    <label for="picture" class="fake-gutter">
                        <a type="button" class="btn btn-outline-secondary">Upload a file</a>
                        <small class="text-muted">Max size: 2MB</small>
                    </label>
                </div>

                {{--Organiser Should be a hidden field, using your logged in user ID--}}
                {{ Form::hidden('organiser_id', Auth::user()->id) }}

                {{--Contact--}}
                <div class="form-group row">
                    {!! Form::label('', 'Contact Details', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                    <div class="col-lg-10">
                        {{ Form::text('contact', Auth::user()->email, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Contact details for the event']) }}
                    </div>
                </div>

                {{--Venue--}}
                <div class="form-group row">
                    {!! Form::label('', 'Venue', ['class' => 'col-lg-2 control-label col-form-label text-md-right']) !!}
                    <div class="col-lg-10">
                        {{ Form::text('venue', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Venue event will take place at']) }}
                    </div>
                </div>

                {{--Create Button--}}
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" id="createSubmitButton" class="btn btn-outline-primary">
                            {{ __('Create Event!') }}
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </fieldset>
        </div>
        {{--Image preview pane--}}
        <div class="col-lg-3">
            <h2>Image Preview</h2>
            <div id="event-image-container">
                <img id="event-image" class="text-md-right text-muted"
                     src="{!!asset('img/events/default/default.svg')!!}" alt=""/>
                <div>
                    <h3 id="event-name-heading"></h3>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(function(){
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

            var files = e.currentTarget.files;

            var fileSize = ((files[0].size / 1024) / 1024).toFixed(4);

            console.log(fileSize);

            //if smaller than max image size
            if (fileSize <= 2 && !isNaN(fileSize) ) {
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
@endsection