{{-- Heading --}}
<div class="col-lg-10 offset-1">
    @if($events->first())
        <div id="event-container">
            <div class="row">
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>Name</h4></a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>Category</h4></a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>Organiser</h4></a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>When</h4></a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>Where</h4></a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ url()->current() }}?order=name"><h4>Likes</h4></a>
                </div>
            </div>
            <hr/>
            {{-- Events --}}
            @foreach($events as $event)
                <div class="row">
                    <div class="col-lg-2">
                        <a href="{{ url('/event/'.$event->urlname) }}">
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
