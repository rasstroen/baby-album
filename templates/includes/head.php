<head>
<title><?php echo $config['title']; ?></title>
<meta name="description" content="<?php echo isset($config['description']) ? $config['description'] : ''; ?>">
<meta name="keywords" content="<?php echo isset($config['keywords']) ? $config['keywords'] : ''; ?>">
<meta name='yandex-verification' content='796cf6969934ac1b' />
<?php
echo "\n<!--css-->\n";
foreach ($config['css'] as $css) {
    echo '<link rel="stylesheet" href="' . $css['href'] . '"/>' . "\n";
}
echo "\n<!--js-->\n";
foreach ($config['js'] as $js) {
    echo '<script src="' . $js['href'] . '"></script>' . "\n";
}
echo "\n";
?>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-34172226-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
