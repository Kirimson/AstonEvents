{{-- Heading --}}
<div class="col-lg-10 offset-1">
    @if($events->first())
        <div id="event-container">
            {{-- Events --}}
            @foreach($events as $event)
                <div class="row">
                    <div class="col-lg-4 event-image-list">
                        <a href="{{ url('/event/'.$event->urlname) }}">
                            <div class="list-image">
                                {{ Html::image($event->picture == null ? asset('img/events/default/default.svg') :
                                $event->picture, null, array('id' => 'event-image-thumb')) }}
                            </div>
                            <h3 id="event-name-heading">{{ $event->UCName }}</h3>
                        </a>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <h4>Description</h4>
                            {{ $event->ShortDescription }}
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-6">
                                <div><h5>Organiser</h5><span>{{ $event->user->name }}</span></div>
                                <hr/>
                                <div><h5>When</h5><span>{{ $event->readableTime }}</span></div>
                            </div>
                            <div class="col-lg-6">
                                <div><h5>Where</h5><span>{{ $event->UCVenue }}</span></div>
                                <hr/>
                                <div><h5>Likes</h5><span>{{ $event->likes }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
            @endforeach
        </div>
    @else
        <h2>Oh. There aren't any events. That's a bit sad...</h2>
    @endif
</div>
