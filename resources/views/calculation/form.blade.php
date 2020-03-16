@extends('base')

@section('content')
    @foreach($errors as $error)
    <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        {{ (string)$error }}
    </div>
    @endforeach
    Available variables
    <ul class="list-group">
        @foreach($availableVariables as $variable)
            <li class="list-group-item">{{ $variable }}</li>
        @endforeach

    </ul>
    <form action="{{ route('calculations.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="exampleFormControlInput1">Variable name</label>
            <input type="text" class="form-control" name="var_name" id="exampleFormControlInput1" required>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Formula</label>
            <input type="text" class="form-control" name="formula" required>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Example textarea</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Submit formula</button>
    </form>
@endsection
