<?php

require_once 'ImageStore.php';
$result = ImageStore::store('/home/img/test.jpg', array(
            'test_image_small' => '50x50x1',
            'test_image_small_cropped' => '50x50x0',
            'test_image_big' => '500x500x1',
            'test_image_big_cropped' => '500x500x0',
                ), 100500);
echo '<pre>';
print_r($result);