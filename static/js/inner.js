fns = [];
function addf(f){
    fns.push({
        cb:f
    });
}

var JSON = JSON || {};

// implement JSON.stringify serialization
JSON.stringify = JSON.stringify || function (obj) {

    var t = typeof (obj);
    if (t != "object" || obj === null) {

        // simple data type
        if (t == "string") obj = '"'+obj+'"';
        return String(obj);

    }
    else {

        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);

        for (n in obj) {
            v = obj[n];
            t = typeof(v);

            if (t == "string") v = '"'+v+'"';
            else if (t == "object" && v !== null) v = JSON.stringify(v);

            json.push((arr ? "" : '"' + n + '":') + String(v));
        }

        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};


// implement JSON.parse de-serialization
JSON.parse = JSON.parse || function (str) {
    if (str === "") str = '""';
    eval("var p=" + str + ";");
    return p;
};

function get_blog_header(id){
    
}

function get_posts_list(){
    var ids = [];
    $('._th_posts_list_item').each(function(){
        ids.push($(this).attr('id').replace(/post(\d+)/,'$1'))
    })
    $('._th_posts_show_item').each(function(){
        ids.push($(this).attr('id').replace(/post(\d+)/,'$1'))
    })

    var params = {
        method:'get_posts_list',
        ids:ids
    };
    $.post('/ajax', params, function(data){
        after_get_posts_list(data);
    },"json");
    
}

function draw_post_list_owner_controls($elem,data){
    $elem.find('.right .title').html($elem.find('.right .title').html()+'<div class="edit_link"><a href="/blog/'+current_profile.id+'/'+data.id+'/edit">редактировать</a></div>')
}

function after_get_posts_list(data){
    if(data && data.posts){
        for (var i in data.posts){
            $elem = $('#post'+data.posts[i].id);
            if(current_profile.id == data.posts[i].blog_id){
                draw_post_list_owner_controls($elem,data.posts[i]);
            }
                
        }
    }
}

function authorization(cb){
    $('#userplace').html('<div id="auth" style="display:none"><div class="login"><div id="email"><em>E-mail: </em> <input id="email_input" /> </div><div id="password"> <em>Пароль: </em> <input id="password_input" type="password" /> </div> <div class="submit"> <input type="button" value="войти" /> </div> <div class="forgot"><a onfocus="this.blur()" href="/passrestore">забыли пароль?</a></div> <div class="reg"><a onfocus="this.blur()" href="/register">зарегистрироваться</a></div> </div> </div> <div id="profile" style="display:none"> <div class="username"><span></span><em></em></div> <div class="blog"><span></span><em></em></div> <div class="album"><span></span><em></em></div> <div class="logout"><span>выход</span></div> </div>')

    cacheddata = getCookie('auth');
    if(cacheddata){
        draw_auth(JSON.parse(cacheddata));
    }

    var params = {
        method:'check_auth'
    };
    $.post('/ajax', params, function(data){
        expire = new Date;
        expire.setDate(expire.getDate()+1)
        if(data && data.profile)
            setCookie('auth', JSON.stringify(data), expire, '/')
        else
            setCookie('auth', null, -1);
        draw_auth(data);
        cb();
    },"json");
}

function logout(){
    document.location.href = '/logout';
}

function album_new_item(id){
    var params = {
        method:'album_new_item',
        id:id
    };
    $.post('/ajax', params, function(data){
        after_album_new_item(data)
    },"json");
}

function after_album_new_item(data){
    if(data && data.profile){
        if(current_profile.id == data.profile.id){
            $('.tp_album_new_item').show();
        }
    }
}

function login(){
    var params = {
        method:'auth',
        email:$('#email_input').val(),
        password:$('#password_input').val()
    };
    $.post('/ajax', params, function(data){
        after_login(data)
    },"json");
}



function register(email){
    if(!isValidEmail(email)) {
        popup_error('Некорректно указан адрес E-mail.');
        return;
    };
    var params = {
        method:'register',
        email:email
    };
    $.post('/ajax', params, function(data){
        after_register(data)
    },"json");
}


function passrestore(email){
    if(!isValidEmail(email)) {
        popup_error('Некорректно указан адрес E-mail.');
        return;
    };
    var params = {
        method:'passrestore',
        email:email
    };
    $.post('/ajax', params, function(data){
        after_passrestore(data)
    },"json");
}


function passrestore_check(hash){
    var params = {
        method:'passrestore_check',
        hash:hash
    };
    $.post('/ajax', params, function(data){
        after_passrestore_check(data)
    },"json");
}

function confirm_email(hash){
    var params = {
        method:'confirm_email',
        hash:hash
    };
    $.post('/ajax', params, function(data){
        after_confirm_email(data)
    },"json");
}

function popup_error(message,cb){
    alert(message);
    if(cb)cb();
}

function popup_message(message,cb){
    alert(message);
    if(cb)cb();
}


function isValidEmail (email, strict){
    if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
    return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

function after_register(data){
    if(data && data.success){
        popup_message('На Ваш Email отправлено письмо для подтверждения регистрации.',function(){
            document.location.href = '/';
        });
        
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Невозможно зарегистрироваться. Попробуйте немного позже.');
    }
}

function after_passrestore(data){
    if(data && data.success){
        popup_message('На Ваш Email отправлено письмо для изменения пароля.',function(){
            document.location.href = '/';
        });
        
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Невозможно восстановить пароль. Попробуйте немного позже.');
    }
}

function after_confirm_email(data){
    if(data && data.success){
        popup_message('Спасибо за подтверждение почтового адреса. Теперь Вы можете войти, используя свой E-mail и пароль, пришедший в письме.',function(){
            document.location.href = '/';
        });
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Невозможно подтвердить адрес почтового ящика.');
    }
}

function after_passrestore_check_(data){
    if(data && data.success){
        popup_message('Теперь Вы можете войти на сайт с новым паролем',function(){
            document.location.href = '/';
        });
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Невозможно изменить пароль для входа на сайт.');
    }
}

function after_passrestore_check(data){
    if(data && data.success){
        _new = prompt('Введите новый пароль');
        _new1 = prompt('Введите новый пароль ещё раз');
        if(!_new){
            popup_error('Введенный пароль слишком короткий');

            after_passrestore_check(data);
        }else if(_new!=_new1){
            popup_error('Введенные пароли не совпадают',function(){
                after_passrestore_check(data);
            });
            
        }
        else{
            var params = {
                method:'after_passrestore',
                _new:_new,
                hash:data.hash
            };
            $.post('/ajax', params, function(data){
                after_passrestore_check_(data)
            },"json");
        }
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Неверная или устаревшая ссылка для восстановления пароля.');
    }
}


function after_login(data){
    if(data && data.success){
        document.location.reload();
    }else{
        if(data && data.error)
            popup_error(data.error);
        else
            popup_error('Невозможно авторизоваться.');
    }
}

var current_profile = {};
function draw_auth(data){
    if(data && data.profile){
        current_profile.id = data.profile.id;
        $('#auth').hide()
        $('#profile').show();
        img = '';
        if(data.profile.avatar_small){
            img = 'background-image: url('+data.profile.avatar_small+');';
        }

        $('#profile').attr('style',img).find('.username span').html('<a onfocus="this.blur()" href="'+data.profile.links.profile+'">'+data.profile.name+'</a>');
        $('#profile').find('.blog span').html('<a onfocus="this.blur()" href="'+data.profile.links.blog+'">блог</a>');
        $('#profile').find('.album span').html('<a onfocus="this.blur()" href="'+data.profile.links.album+'">альбом</a>');
        $('#profile').find('.logout span').css('cursor','pointer').bind('click',function(){
            logout()
        })
        if(data.profile.counter)
            $('#profile').find('.username em').text(data.profile.counter);
    }else{
        $('#profile').hide();
        $('#auth').show().find('.login').show().find('.submit input').bind('click',function(){
            login()
        });
        $('#password_input').keypress(function (event) {
            if (event.which == '13'){
                login();
            }
        });
    }
}


function get_profile_page(id){
    if(current_profile.id){
        var params = {
            method:'get_profile_page',
            id:id
        };
        $.post('/ajax', params, function(data){
            after_get_profile_page(data)
        },"json");
    }
}

function after_get_profile_page(data){
    if(data && data.profile){
        if(data.profile.id == current_profile.id)
            $('.user_profile .left em').html('<a onfocus="this.blur()" href="/user/'+data.profile.id+'/edit">редактировать профиль</a>')
    }
    
}

function after_check_avail_name(data,el){
    if(data && data.success)
        el.next('span').html('свободно');
    else
    if(data && data.error)
        el.next('span').html(data.error);
    else
        el.next('span').html('занято');
}

function check_avail_name(val,el){
    var params = {
        method:'check_avail_name',
        name:val
    };
    $.post('/ajax', params, function(data){
        after_check_avail_name(data,el)
    },"json");
        
    
}

function get_profile_edit_page(id){
    $('.user_profile .left .name input').bind('keyup',function(){
        check_avail_name($(this).val(),$(this))
    })
    if(current_profile.id){
        var params = {
            method:'get_profile_edit_page',
            id:id
        };
        $.post('/ajax', params, function(data){
            after_get_profile_edit_page(data)
        },"json");
    }
}

function after_get_profile_edit_page(data){
    if(data && data.profile){
       
    }
}

function setCookie (name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
    ((expires) ? "; expires=" + expires : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}

function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}

function after_album_timeline(data){
    if(data && data.ai){
        for (var i in data.ai){
            $elem = $('#ai_'+data.ai[i].id);
            if(data.ai[i].album_id == current_profile.id){
                $elem.find('.edit').show()
            }
        }
    }
}

var image_box_width = 470;
var image_box_height = 400;
var image_box_height_d = image_box_height-320;
function album_timeline(small){
    if(small){
        image_box_width = 280;
        image_box_height = 220;
        image_box_height_d = image_box_height-180;
    }
    var ids = [];
    $('.ai').each(function(){
        id = $(this).attr('id').replace(/ai_/,'');
        ids.push(id);
    });
    if(ids && ids.length){
        var params = {
            method:'album_timeline',
            ids:ids
        };
        $.post('/ajax', params, function(data){
            after_album_timeline(data)
        },"json");
    }
    normalimagefield=small?'thumb_':'normal_';
    $('.t1 .photobox').each(function(){
        id = $(this).attr('id').replace(/photo_/,'');
        nh = sizes[id][normalimagefield+'height']-0+image_box_height_d;
        $(this).find('.dimg').css('width',sizes[id][normalimagefield+'width']).css('height',sizes[id][normalimagefield+'height']);
        $(this).find('.dimg').css('position','absolute').css('top',Math.round((nh-sizes[id][normalimagefield+'height'])/2))
        $(this).find('.dimg').css('position','absolute').css('left',Math.round((image_box_width-sizes[id][normalimagefield+'width'])/2))
        $(this).parent().parent().css('height',nh)
        //$(this).parent().parent().css('height',nh).css('margin-top',Math.round((image_box_height-nh)/6));
        //$(this).parent().parent().css('height',nh).css('padding-bottom',Math.round((image_box_height-nh)/6));
    });
}


$(function(){
    authorization(function(){
        for(var i in fns)
            fns[i].cb();
    })
    $('.timeago').timeago();
})

