function change_vk_profile(){
    document.location.href = 'https://oauth.vk.com/authorize?client_id='+vk_app_id+'&redirect_uri=http://balbum.ru/connect/vk&scope=friends,photos,notify,groups,offline&display=page';
}

function change_fb_profile(){
    document.location.href = 'https://www.facebook.com/dialog/oauth?client_id='+fb_app_id+'&redirect_uri=http://balbum.ru/connect/fb&scope=offline_access,user_photos,user_checkins,user_groups&response_type=code';
}

function change_ok_profile(){
    document.location.href = 'http://www.odnoklassniki.ru/oauth/authorize?client_id='+ok_app_id+'&scope=PHOTO CONTENT&response_type=code&redirect_uri=http://balbum.ru/connect/ok';
}


