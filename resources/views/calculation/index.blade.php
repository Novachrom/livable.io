@extends('base')

@section('content')
    <a href="{{ route('calculations.create') }}" class="btn btn-primary">Add new</a>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Variable name</th>
            <th scope="col">Formula</th>
            <th scope="col">Description</th>
        </tr>
        </thead>
        <tbody>
        @foreach($calculations as $index => $calculation)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $calculation->var_name }}</td>
                <td>{{ $calculation->formula }}</td>
                <td>{{ $calculation->description }}</td>
        @endforeach
        </tbody>
    </table>
@endsection
