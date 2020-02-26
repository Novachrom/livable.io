@extends('base')

@section('content')
    @parent
    Countries:
    <div class="list-group">
        @foreach($countries as $country)
            <a href="{{ route('country', [$country->name]) }}" class="list-group-item list-group-item-action">{{$country->name}}</a>
        @endforeach
    </div>
@endsection
