<html>
    <head><head>
    <body>
     @foreach ($shift as $value )
         <p>Id:{{$value ->id}}</p>
         <p>start_date_time:{{$value ->start_date_time}}</p>
         <p>end_date_time:{{$value ->end_date_time}}</p>
         <p>created_at:{{$value ->created_at}}</p>
         <p>updated_at:{{$value ->updated_at}}</p>
     @endforeach

    </body>
</html>
