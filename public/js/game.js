var ANIM_CLOSE = 700;
var ANIM_PRE = 50;
var ANIM_START = 450;

var BLOCK_SIZE = 64;
var MAX_PRE_TIME = 5;
var TIMER_INTERVAL = 1000;

var UPPER_BOUND = 90;
var ADD_POINTS = 5;
var TAKE_POINTS = 1;

var state = 0;
var timer_id;
var move_stack = [];
var opened_blocks = 0;
var colors = [];

function init_map(){
	if(MAX_BLOCKS % MAX_CURRENT != 0){
		alert('Error, MAX_BLOCKS should be divided by MAX_CURRENT!');
		return false;
	}else if(Math.sqrt(MAX_BLOCKS) % 1 != 0){
		alert('Error, sqrt of MAX_BLOCKS is not an integer!');
		return false;
	}
	
    $('.window').width(Math.sqrt(MAX_BLOCKS) * BLOCK_SIZE);
    $('.window').height(Math.sqrt(MAX_BLOCKS) * BLOCK_SIZE);

    var code = '';
    getRandomColors();

    for (var i = 0; i < MAX_BLOCKS; i++) {
        code += '<div class="block" id="b-' + i + '" color="' + colors[i] +'">';
        code += '<div class="block-cover" id="c-' + i + '"></div>';
        code += '</div>';
    }

    $('.front').html(code);
	return true;
}

function getRandomColors(){
    var arr = [];
    for (var i = 0; i < MAX_BLOCKS; i++) {
        colors[i] = -1; // init
        arr[i] = i;
    }

    var current_color = getCurrentColor();
    var color_count = 0, current_index;

    for (var j = 0; j < MAX_BLOCKS; j++){
        current_index = Math.floor(Math.random() * arr.length);
        colors[arr[current_index]] = current_color;

        ++color_count;
        if(color_count > MAX_CURRENT-1){
            current_color = getCurrentColor();
            color_count = 0;
        }

        // excluding index
        arr.splice(current_index, 1);
    }
}

function getCurrentColor(){
    //Math.floor(Math.random() * (max - min)) + min;
    // min - included, max - not;
    return 1 + Math.floor(Math.random() * MAX_COLORS + 1 - 1);
}

$(document).ready(function () {
    if(!init_map()){
		window.location = '/';
		return;
	}

    $('.window').offset({
        top: ($(window).height() - $('.window').height()) / 2
    })
    $('.window').css('display', 'block');

    setTimeout(function () {
        $('.window').removeClass('window-start');

        setTimeout(function () {
            $('.window').find('.block').each(function (index) {
                setColor($(this), $(this).attr('color'));
            });

            $('#timer').html(toStr(MAX_REMEMBER_TIME));
            $('body').append('<style>#timer:hover:before{ width: ' + $('#timer').width() + 'px; }</style>');
            timer_id = setInterval(timer, TIMER_INTERVAL);
        }, ANIM_START);
    }, ANIM_PRE)
});

$(document).delegate('.block', 'click', function () {
    if ($(this).attr('opened') || state != 2) return;

    move_stack.push($(this));
    setColor($(this), parseInt($(this).attr('color')));

    if (move_stack.length >= MAX_CURRENT) {
        var defcolor = move_stack[0].attr('color');

        for (var i = 0; i < move_stack.length; i++) {
            if (defcolor != move_stack[i].attr('color')) {
                closeCells(move_stack, 0);
                move_stack = [];
                return;
            }
        }

        closeCells(move_stack, MAX_COLORS + 1);
        move_stack = [];
    }
});

function setColor(obj, color) {
    if (color > 0) {
        obj.attr('opened', '1');

        if (color > MAX_COLORS) {
            var id = obj.attr('id').split('-')[1];
            $('#c-' + id).css('display', 'block');
        }
        else {
            obj.addClass('block-special-' + color);
        }
    }
    else {
        obj.removeAttr('opened');

        for (var i = 1; i <= MAX_COLORS; i++) {
            obj.removeClass('block-special-' + i);
        }
    }
}

function closeCells(stack, color) {
    setTimeout(function () {
        if (color > MAX_COLORS) {
            addPoints(ADD_POINTS);
            opened_blocks += MAX_CURRENT;

            if (opened_blocks >= MAX_BLOCKS) {
                clearInterval(timer_id);
                finish(0);
                state++;
            }
        } else if (color == 0) {
            addPoints(-TAKE_POINTS);
        }

        while (stack.length > 0) {
            setColor(stack.pop(), color);
        }
    }, ANIM_CLOSE);
}

function addPoints(amount) {
    $('#points').html(parseInt($('#points').html()) + amount);
}

function toStr(val) {
    var str = '';
    if (val / 60 < 10) str += '0';
    str += parseInt(val / 60) + ':';
    if (val % 60 < 10) str += '0';
    return str + (val % 60);
}

function toTime(str) {
    var ms = str.split(':')[0] * 60;
    return ms + parseInt(str.split(':')[1]);
}

function timer() {
    var time = toTime($('#timer').html()) - 1;

    if (time < 0) {
        clearInterval(timer_id);

        if (state == 0) {
            $('.window').find('.block').each(function (index) {
                setColor($(this), 0);
            });

            $('#timer').html(toStr(MAX_PRE_TIME));
            timer_id = setInterval(timer, TIMER_INTERVAL);
        } else if (state == 1) {
            $('#timer').html(toStr(MAX_GAME_TIME));
            timer_id = setInterval(timer, TIMER_INTERVAL);
        } else {
            finish(1);
        }

        state++;
    } else {
        $('#timer').html(toStr(time));
    }
}

function finish(status) {
    $('.window').addClass('window-rotated');
    $('.window').offset({
        top: ($(window).height() - 640) / 2
    });

    $('#timer').css('display', 'none');
    $('#points').css('display', 'none');

    var points = $('#b-points');
    points.html(points.html() + $('#points').html());

    var timer = $('#b-timer');
    timer.html(timer.html() + toTime($('#timer').html()) + 's');

    var opened = $('#b-opened');
    opened.html(opened.html() + opened_blocks);

    var percents = $('#b-percents');
    var int_percents = parseInt(100 * $('#points').html() / (MAX_BLOCKS / MAX_CURRENT * ADD_POINTS));
    if (int_percents < 0) int_percents = 0;
    percents.html(percents.html() + int_percents + '%');

    if (status > 0) {
        $('#b-title').html('The time has expired!');
    } else {
        $('#b-title').html('You opened the map!');
    }

    if (int_percents < UPPER_BOUND) {
        $('#b-result').html('You didn\'t get the passing score!');
        $('#b-result').addClass('result-fail');
    } else {
        $('#b-result').html('You achieved a new level!');
        $('#b-result').addClass('result-ok');
    }

    $('.hidden-msg').removeClass('hidden-msg');
}

$('#timer').click(function(){
    if(state != 1 && timer_id){
        clearInterval(timer_id);
        $('#timer').html('00:00');
        timer();
    }
});