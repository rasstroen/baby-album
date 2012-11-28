<pre><?php
$dev_mode = 1;
$core_path = 'core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
require_once $core_path . 'include.php';


if (isset($_POST['x'])) {
    echo date('Y-m-d H:i:s') . "\n";
    $image_id = ImgStore::upload($_FILES['photo']['tmp_name'], array(1 => '100x100x0', 2 => '500x500x1', 3 => '250x250x1'));
    echo date('Y-m-d H:i:s') . "\n";
    echo '<img src="' . ImgStore::getUrl($image_id, 0) . '">' . "\n";
    echo '<img src="' . ImgStore::getUrl($image_id, 1) . '">' . "\n";
}
?>
<form enctype="multipart/form-data" method="post">
    <input type="hidden" name="x">
    <input type="file" name="photo" >
    <input type="submit">
</form>