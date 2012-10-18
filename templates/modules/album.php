<?php

function tp_album_edit_event($data) {

}

function _th_draw_event_in_list($event) {
    $self = CurrentUser::$id = $event['user_id'];
    ?>
    <div class="event">
        <?php if ($self) {
            ?><div class="edit"><a href="/album/<?php echo $event['album_id']; ?>/event/<?php echo $event['id']; ?>/edit">редактировать</a></div><?
    }
        ?>
    </div>
    <?php
}

function tp_album_list_events($data) {
    ?><div class="album">
        <?php
        foreach ($data['events'] as $event) {
            _th_draw_event_in_list($event);
        }
        ?>
    </div><?php
}