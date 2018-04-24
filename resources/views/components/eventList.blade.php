{{-- Heading --}}
@if($includeHeading)
    @include('components.searchHeading',
            array('orderBy' => app('request')->input('orderBy'), 'order' => app('request')->input('order')))
@endif
<div class="col-lg-10 offset-1">
    @if($events->first())
        <div id="event-container">
            {{-- Events --}}
            @foreach($events as $event)
                <div class="row">
                    <div class="col-lg-4">
                        <div class="list-image">
                            <a href="{{ url('/event/'.$event->urlname) }}">
                                {{ Html::image($event->picture == null ? asset('img/events/default/default.svg') :
                                $event->picture, null, array('id' => 'event-image-thumb')) }}
                                <h3 id="event-name-heading">{{ $event->UCName }}</h3>
                            </a>
                            <span>Category: {{ $event->UCCategory }}</span>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <h4>Description</h4>
                            <div id="event-description">
                                {{ $event->ShortDescription }}
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-6">
                                <div><h5>Organiser</h5><span>{{ $event->user->username }}</span></div>
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
        <h2>Sorry, no events could be found!</h2>
    @endif
</div>
<script src="{{ asset('js/manageSort.js') }}"></script>
