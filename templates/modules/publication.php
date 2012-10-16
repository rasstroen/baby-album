<?php

/**
 *
 * @author mchubar
 */
function th_draw_publication_tags($publication, $url = '/publication/tag/') {
    $tags = explode(',', $publication['tags']);
    $indexes = explode(',', $publication['tags_indexes']);
    foreach ($tags as $key => $tag_title) {
        ?><span class="tag"><a href="<?php echo $url . $indexes[$key]; ?>"><?php echo $tag_title; ?></a></span><?php
    }
}

function th_draw_publication_list_item($publication, $tags_url = '/publication/tag/') {
    ?>
    <div class="pub">
        <div class="head">
            <div class="user">
                <a href="/u/<?php echo $publication['user_id'] ?>"><?php echo th_username($publication['user']); ?></a>
            </div>
            <h3><a href="/publication/<?php echo $publication['id']; ?>"><?php echo $publication['title']; ?></a></h3>
        </div>
        <div class="body">
            <?php echo $publication['text_short']; ?>
        </div>
        <div class="foot">
            <div class="tags">
                <?php th_draw_publication_tags($publication, $tags_url); ?>
            </div>
        </div>
    </div>
    <?php
}

function th_draw_publication_item($publication, $tags_url = '/publication/tag/') {
    ?>
    <div class="pub">
        <div class="head">
            <div class="user">
                <a href="/u/<?php echo $publication['user_id'] ?>"><?php echo th_username($publication['user']); ?></a>
            </div>
            <h3><a href="/publication/<?php echo $publication['id']; ?>"><?php echo $publication['title']; ?></a></h3>
        </div>
        <div class="body">
            <?php echo $publication['text']; ?>
        </div>
        <div class="foot">
            <div class="tags">
                <?php th_draw_publication_tags($publication, $tags_url); ?>
            </div>
        </div>
    </div>
    <?php
}

/*
 * Одна публикация
 */

function tp_publication_show_item($data) {
    ?>
    <div class="block publications_item">
        <?php
        foreach ($data['publications'] as $publication) {
            th_draw_publication_item($publication, '/publication/tag/');
        }
        ?>
    </div>
    <?php
}

/**
 * Список публикаций на разводной странице
 * @param type $data
 */
function tp_publication_list_main($data) {
    ?>
    <div class="block publications_main">
        <h2>Популярные публикации</h2>
        <div class="info gray">
            На нашем сайте собраны только проверенные и уникальные статьи в помощь мамам и папам. Вы можете предложить свою статью специальной кнопкой в редактировании записи в своем личном блоге.
        </div>
        <div class="publications">
            <?php
            foreach ($data['publications'] as $publication) {
                th_draw_publication_list_item($publication, '/publication/tag/');
            }
            ?>
        </div>
    </div>
    <?php
}