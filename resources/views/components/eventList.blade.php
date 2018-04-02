{{-- Heading --}}
<div class="col-lg-10 offset-1">
{{--    {{ $events-> }}--}}
    @if($events->first())
        <div id="event-container">
            <div class="row">
                <div class="col-lg-2">
                    <h3>Name</h3>
                </div>
                <div class="col-lg-2">
                    <h4>Category</h4>
                </div>
                <div class="col-lg-2">
                    <h4>Organiser</h4>
                </div>
                <div class="col-lg-2">
                    <h4>When</h4>
                </div>
                <div class="col-lg-2">
                    <h4>Where</h4>
                </div>
                <div class="col-lg-2">
                    <h4>Likes</h4>
                </div>
            </div>
            <hr/>
            {{-- Events --}}
            @foreach($events as $event)
                <div class="row">
                    <div class="col-lg-2">
                        <a href="{{ url('/event/'.$event->name) }}">
                            <div id="event-image-container">
                                {{ Html::image($event->picture == null ? asset('img/events/default/default.svg') :
                                $event->picture, null, array('id' => 'event-image-thumb')) }}
                                <h3>{{ $event->name }}</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2">
                        {{ $event->UCCategory }}
                    </div>
                    <div class="col-lg-2">
                        {{ $event->user->name }}
                    </div>
                    <div class="col-lg-2">
                        {{ $event->readableTime }}
                    </div>
                    <div class="col-lg-2">
                        {{ $event->UCVenue }}
                    </div>
                    <div class="col-lg-2">
                        {{ $event->likes }}
                    </div>
                </div>
                <hr/>
            @endforeach
        </div>
    @else
        <h2>Oh. There aren't any events. That's a bit sad...</h2>
    @endif
</div>
