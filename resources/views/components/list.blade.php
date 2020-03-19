<div class="row row-cols-1 row-cols-md-3">
    @foreach($cities as $city)
        <div class="card mb-12" style="min-width: 900px;">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <div class="city-list-photo">
                        @if(empty($city->photo))
                            {{--                todo: image placeholder --}}
                        @else
                            <img src="{{ $city->photo }}" class="card-img-top" alt="{{ $city->name }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('city.details', [$city->country->name, $city->name]) }}">{{ $city->name }}, {{ $city->country->name }}</a></h5>
                        <p class="card-text">Cost of living: {{$city->getCostOfLivingWithCurrency()}}</p>
{{--                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>--}}
                        <a href="{{ route('city.details', [$city->country->name, $city->name]) }}" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
