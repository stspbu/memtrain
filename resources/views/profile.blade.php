@extends('index')

<?php
use \App\Http\Controllers\ResponseController;
?>

@section('header')
@stop

@section('content')
    @for ($i = 0; $i < ResponseController::MAX_LEVEL; $i++)
    <a href="/game?l={{$i}}">Go to #{{$i+1}}</a>
    @endfor
@stop

@section('footer')
@stop