<?php

function tp_comments_list_album_event($data) {
    ?><div class="comments_album_event"><?php
    _th_draw_comment_leave_form($data);
    ?>
    </div>
    <?php
}

function _th_draw_comment_leave_form($data) {
    $object_id = isset($data['object_id']) ? $data['object_id'] : 0;
    if (!CurrentUser::$authorized)
        return;
    $user = Users::getByIdLoaded(CurrentUser::$id);
    ?>
    <div class="comment_form">
        <form method="post">
            <input type="hidden" name="writemodule" value="comment" />
            <input type="hidden" name="object_type" value="<?php echo Config::COMMENT_OBJECT_ALBUM_EVENT; ?>" />
            <input type="hidden" name="object_id" value="<?php echo $object_id; ?>" />
            <input type="hidden" name="action" value="add_comment" />
            <div class="head">
                <span>Комментарий</span>
                <div><textarea name="text"></textarea></div>
            </div>
            <div class="submit">
                <input type="submit" value="Отправить" />
            </div>
        </form>
    </div>
    <?php
}