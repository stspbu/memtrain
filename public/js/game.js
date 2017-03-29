var ANIM_DELAY = 800;

var colors = [
    'white',    // def
    'yellow',
    'red',
    'blue',
    'orange',
    'green'
];

var in_progress = 0;

$('.game-block').click(function () {
    if(in_progress > 0 || $(this).attr('opened')) return;

    var gid = $(this).attr('id').split('-')[1];
    // $(this).css('background-color', 'gray');

    $.ajax({
        url: '/ajax/open',
        method: "post",
        data: {
            gid: gid,
            _token: glToken
        },
        success: function (data) {
            console.log(data);

            if(data['color']){
                setColor(gid, data['color']);

                if(data['status'] != 0){
                    in_progress = 1;
                    setTimeout(function(){
                        setColors(data['blocks'], 0);
                        in_progress = 0;
                    }, ANIM_DELAY);
                }
            }
        },
        error: function(data){
            console.log("Error: " + data);
        }
    });
});

function setColors(arr, color){
    for(var i = 0; i < arr.length; i++){
        setColor(arr[i], color);
    }
}
function setColor(id, color){
    if(color > 0){
        $('#b-' + id).attr('opened', '1');
        $('#b-' + id).addClass('game-block-click');
    }
    else{
        $('#b-' + id).removeAttr('opened');
        $('#b-' + id).removeClass('game-block-click');
    }

    $('#b-' + id).css('background-color', colors[color]);
}