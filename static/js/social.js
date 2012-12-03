function change_vk_profile(){
    document.location.href = 'https://oauth.vk.com/authorize?client_id='+vk_app_id+'&redirect_uri=http://balbum.ru/connect/vk&scope=photos,notify,groups,offline&display=page';
}