<?php

/**
 *
 * @author mchubar
 */
function tp_sape_show_links($data) {
    foreach ($data['links'] as $link) {
        ?>
        <span class="slink"><?php echo $link ?></span>
        <?php
    }
}