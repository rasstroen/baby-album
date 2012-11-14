function like(o,id){

    $.post('/?like', {
        method:'like',
        ids:id,
        plus:!$(o).hasClass('active')
    }, function(data){
        $.post('/?get_likes', {
            method:'get_likes',
            ids:[id]
        }, function(data){
            draw_likes(data) ;
        },"json");
    },"json");
}
function draw_likes(data){
    if(data && data.likes){
        for(var i in data.likes){
            var txt = '';
            var txt_users = [];
            $('#l'+i).show();
            if(data.self && (data.self[i]>0)){
                $('#l'+i).find('a').addClass('active');
                txt = ('Нравится <a href="/u/'+data.self[i]+'">Вам</a>');
            }else{
                $('#l'+i).find('a').removeClass('active');
                if(data.owner){
                    $('#l'+i).find('a').show();
                }
            }
            if(!data.owner){
                $('#l'+i).find('a').hide();
            }
            for (var j in data.likes[i]){
                if(!data.self || (data.likes[i][j].id != data.self[i]))
                    txt_users.push('<i class="user"><a href="/u/'+data.likes[i][j].id+'">'+data.likes[i][j].nickname+'</a></i>')
            }
            _left = (txt!='')?txt:'';
            _right = (txt!='')?(txt_users.length?', '+txt_users.join(', '):''):(txt_users.length?'Нравится '+txt_users.join(', '):'');
            $('#l'+i).find('em').html(_left+_right);

        }
    }
}
$(function(){
    var likes = {};
    $('.like').each(function(){
        id = $(this).attr('id').replace(/l/,'');
        if(id-0){
            likes[id-0]=id-0;
        }
    })
    if(likes){
        $.post('/?get_likes', {
            method:'get_likes',
            ids:likes
        }, function(data){
            draw_likes(data) ;
        },"json");
    }
})