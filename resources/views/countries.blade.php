@extends('base')

@section('content')
    @parent
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($countries as $index => $country)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{$country->name}}</td>
                <td>
                    <a href="{{ route('country.cities', [$country->name]) }}" class="btn btn-primary">Cities</a>
                    <a href="{{ route('country.details', [$country->name]) }}" class="btn btn-success">Details</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
