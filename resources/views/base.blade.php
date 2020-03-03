<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Livable.ai</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <link rel="stylesheet" href="{{ url('css/style.css?v=').date('Y-m-d_H-i-s') }}">
    </head>
    <body>
    <div class="content">
        <div class="title m-b-md">
            <a class="logo" href="{{ url('/') }}">Livable.ai</a>
        </div>
    </div>
    <div class="container">
        <form class="form-inline" action="{{ route('search') }}">
            <label class="sr-only" for="inlineFormInputName2">Search</label>
            <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="search" placeholder="Search">

            <button type="submit" class="btn btn-primary mb-2">Search</button>
        </form>
        @yield('content')
    </div>
    <script src="{{ url('js/main.js') }}"></script>
    </body>
</html>
