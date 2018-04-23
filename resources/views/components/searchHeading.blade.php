<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#sort-headings" aria-expanded="false" aria-controls="collapseExample">
    Sort
</button>
<div class="collapse row" id="sort-headings">
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('name', '{{ url()->full() }}')">Name
            @if($orderBy == "name" || $orderBy == "")
                    <i class="fas {{ $order == "descending" ? "fa-angle-down" : "fa-angle-up"}}"></i>
            @endif
            </a>
        </h4>
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('category', '{{ url()->full() }}')">Category</a>
            @if($orderBy == "category")
                <i class="fas {{ $order == "ascending" ? "fa-angle-up" : "fa-angle-down"}}"></i>
            @endif
        </h4>
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('organiser_id', '{{ url()->full() }}')">Organiser</a>
            @if($orderBy == "organiser_id")
                <i class="fas {{ $order == "ascending" ? "fa-angle-up" : "fa-angle-down"}}"></i>
            @endif
        </h4>
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('time', '{{ url()->full() }}')">When</a>
            @if($orderBy == "time")
                <i class="fas {{ $order == "ascending" ? "fa-angle-up" : "fa-angle-down"}}"></i>
            @endif
        </h4>
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('venue', '{{ url()->full() }}')">Where</a>
            @if($orderBy == "venue")
                <i class="fas {{ $order == "ascending" ? "fa-angle-up" : "fa-angle-down"}}"></i>
            @endif
        </h4>
        <h4 class="col-lg-2">
            <a href="#" onclick="sort('likes', '{{ url()->full() }}')">Likes</a>
            @if($orderBy == "likes")
                <i class="fas {{ $order == "ascending" ? "fa-angle-up" : "fa-angle-down"}}"></i>
            @endif
        </h4>
</div>
<hr/>