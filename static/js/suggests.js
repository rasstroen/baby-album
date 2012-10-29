var album_id = 0;
function init_hide_unhide(){
    $('.hide a').bind('click',function(){
        hide_suggest($(this),1);
    });
    $('.unhide a').bind('click',function(){
        hide_suggest($(this),0);
    });
}

function hide_suggest(o, hide){
    event_id = o.parent().parent().attr('id').replace(/e_/,'');
    var params = {
        album_id:album_id,
        method:hide?'album_hide_suggest':'album_show_suggest',
        event_id:event_id
    };

    $.post('/', params, function(data){
        after_hide(data, event_id,hide);
    },"json");
}

function after_hide(data, event_id, hide){
    if(hide){
        $('#e_'+event_id).addClass('hidden');
    }else{
        $('#e_'+event_id).removeClass('hidden')
    }
}