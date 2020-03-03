@extends('base')

@section('content')
    <h2>{{$country->name}}</h2>
    @if(!empty($country->oecd_bli_data))
        <h3>Oedc BLI Data:</h3>
        <ul class="list-group">
            @foreach($country->oecd_bli_data as $field => $value)
                <li class="list-group-item">{{ $field }}: {{ $value }}</li>
            @endforeach
        </ul>
    @endif
@endsection
