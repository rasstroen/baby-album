<?php

function tp_comments_list_album_event($data) {
    ?><div class="comments_album_event"><?php
    _th_draw_comment_leave_form($data);
    ?>
    </div>
    <?php
}

global $i;

function _th_draw_comments($comments, $level = 1) {
    global $i;
    foreach ($comments as $comment) {
        $comment_user = $comment['user'];
        ?>
        <div class="comment comment_<?php echo $level; ?> comment3_<?php echo (floor($level / 3) > 1) ? 'eq3' : 'eq0'; ?>">
            <a name="comment-<?php echo $comment['id']; ?>"></a>
            <div class="body <?php echo ( ($i++) % 2) ? 'odd ' : 'nodd ' ?>">
                <div class="user">
                    <div class="av">
                        <?php
                        /* @var $comment_user User */
                        ?>
                        <a href="/u/<?php echo $comment_user->id; ?>"><img src="<?php echo $comment_user->getAvatar(); ?>"/></a>
                    </div>
                </div>
                <div class="text"><?php echo $comment['text'] ?></div>
            </div>
            <?php
            if (isset($comment['childs']) && count($comment['childs'])) {
                _th_draw_comments($comment['childs'], $level + 1);
                ?><?php
        }
            ?>
        </div>
        <?php
    }
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
                <span>Ваш комментарий...</span>
                <div class="avatar">
                    <img src="<?php echo $user->getAvatar(); ?>">
                </div>
                <div class="text"><textarea name="text"></textarea></div>
            </div>
            <div class="submit">
                <input type="submit" value="Отправить" />
            </div>
        </form>
    </div>
    <div class="comments">
        <?php _th_draw_comments($data['comments']); ?>
    </div>
    <?php
}