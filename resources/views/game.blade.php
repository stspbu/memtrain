@extends('index')

@section('header')
    <link rel="stylesheet" href="css/content.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
@stop

@section('content')
    <div class="game-window">
        @for ($i = 0; $i < \App\Http\Controllers\ResponseController::MAX_CELLS; $i++)
            <div class="game-block" id="b-{{$i}}"></div>
        @endfor
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        // needed for ajax and some other shit
        var glToken = "<?php echo Session::token() ?>";
    </script>
    <script type="text/javascript" src="js/game.js"></script>
@stop