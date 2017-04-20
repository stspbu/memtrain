@extends('index')

<?php
use \App\Http\Controllers\ResponseController;

define('BLOCKS', 0);
define('CURRENT', 1);
define('COLORS', 2);
define('REMEMBER_TIME', 3);
define('GAME_TIME', 4);

// db ...

$level = isset($_GET['l']) ? (int)$_GET['l'] : 0;
if ($level >= ResponseController::MAX_LEVEL) $level = 0;
?>

@section('header')
    <link rel="stylesheet" href="../css/content.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
@stop

@section('content')
    <div class="stats">
        <div id="points">0</div>
    </div>

    <div class="stats stats-left">
        <div id="timer"></div>
    </div>

    <div class="window window-start">
        <div class="front">
        </div>
        <div class="back">
            <div class="back-stats hidden-msg">
                <div id="b-title"></div>
                <div id="b-points">Game points: </div>
                <div id="b-timer">Remaining time: </div>
                <div id="b-opened">Opened blocks: </div>
                <div id="b-percents">Accuracy: </div>
                <div id="b-result"></div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        // needed for ajax and some other shit
        var glToken = "<?php echo Session::token() ?>";

        /* varied changing constants */
        var MAX_BLOCKS = {{ResponseController::PARAMS_TABLE[$level][BLOCKS]}};
        var MAX_CURRENT = {{ResponseController::PARAMS_TABLE[$level][CURRENT]}};
        var MAX_COLORS = {{ResponseController::PARAMS_TABLE[$level][COLORS]}};

        var MAX_REMEMBER_TIME = {{ResponseController::PARAMS_TABLE[$level][REMEMBER_TIME]}};
        var MAX_GAME_TIME = {{ResponseController::PARAMS_TABLE[$level][GAME_TIME]}};
    </script>
    <script type="text/javascript" src="../js/game.js"></script>
@stop