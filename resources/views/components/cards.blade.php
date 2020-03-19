<div class="row row-cols-1 row-cols-md-3">
    @foreach($cities as $city)
    <div class="col mb-4">
        <div class="card">
            <div class="city-photo">
            @if(empty($city->photo))

            @else
                <img src="{{ $city->photo }}" class="card-img-top" alt="{{ $city->name }}">
            @endif
            </div>
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('city.details', [$city->country->name, $city->name]) }}">{{ $city->name }}, {{ $city->country->name }}</a></h5>
{{--                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Cost of living: {{$city->getCostOfLivingWithCurrency()}}</li>
            </ul>
{{--            <div class="card-footer">--}}
{{--                <small class="text-muted">Last updated 3 mins ago</small>--}}
{{--            </div>--}}
        </div>
    </div>
    @endforeach
</div>
