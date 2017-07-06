<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style type='text/css'>
            thead td {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>{{ config('app.name') }}</h1>

        @if(!empty($error)) 
            <div>Error: {{$error}}</div>
        @else
            <table class='table'>
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Link</td>
                        <td>Status</td>
                        <td>Ratio</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($pins as $pin)
                    <tr>
                        <td>{{$pin->id}}</td>
                        <td>
                            @if(!empty($pin->link))
                                <a href='{{$pin->link}}'>{{$pin->link}}</a>
                            @else
                                No Link
                            @endif
                        </td>
                        @if($pin->valid)
                            <td class='bg-success'>
                        @else
                            <td class='bg-danger'>
                        @endif
                            {{$pin->status}}
                        </td>
                        @if($pin->ratio < 1.2)
                            <td class='bg-danger'>
                        @else 
                            <td class='bg-success'>
                        @endif
                            {{$pin->ratio}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    </body>
</html>
