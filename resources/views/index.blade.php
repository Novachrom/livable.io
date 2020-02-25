<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Livableai</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .full-height {
                height: 100vh;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
            .position-ref {
                position: relative;
            }
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .content {
                text-align: center;
            }
            .title {
                font-size: 84px;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
    <div class="content">
        <div class="title m-b-md">
            Livableai
        </div>
    </div>
    <div class="container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">City</th>
                <th scope="col">Country</th>
                <th scope="col">Cost of living</th>
                <th scope="col">Health Care Index</th>
                <th scope="col">Crime Index</th>
                <th scope="col">Traffic Time Index</th>
                <th scope="col">Quality of Life Index</th>
                <th scope="col">Restaurant Price Index</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cities as $index => $city)
                <tr>
                    <th scope="row">{{$index+1}}</th>
                    <td>{{$city->name}}</td>
                    <td>{{$city->country->name}}</td>
                    <td>{{$city->cost_of_living}}</td>
                    <td>{{$city->health_care_index}}</td>
                    <td>{{$city->crime_index}}</td>
                    <td>{{$city->traffic_time_index}}</td>
                    <td>{{$city->quality_of_life_index}}</td>
                    <td>{{$city->restaurant_price_index}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="content">{{ $cities->links() }}</div>

    </div>
    </body>
</html>
