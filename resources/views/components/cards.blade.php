<div class="row row-cols-1 row-cols-md-3">
    @foreach($cities as $city)
    <div class="col mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $city->name }}, {{ $city->country->name }}</h5>
{{--                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Cost of living: ${{$city->cost_of_living}} / mo</li>
            </ul>
{{--            <div class="card-footer">--}}
{{--                <small class="text-muted">Last updated 3 mins ago</small>--}}
{{--            </div>--}}
        </div>
    </div>
    @endforeach
</div>
