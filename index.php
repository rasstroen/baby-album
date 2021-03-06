<?php

$dev_mode = 1;
$core_path = 'core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
require_once $core_path . 'include.php';
if (isset($_SERVER['REQUEST_URI'])) {
    if ($_SERVER['REQUEST_URI'] === '/logout') {
        $cookie_key = Config::need('COOKIE_KEY', 'u');
        $hash_coookie_key = $cookie_key . '_sh';
        $uid_coookie_key = $cookie_key . '_id';
        setcookie($hash_coookie_key, false, time(), '/', '.' . Config::need('www_domain'));
        setcookie($uid_coookie_key, false, time(), '/', '.' . Config::need('www_domain'));
        header('Location: /', 301);
        exit(0);
    }
}



if ($dev_mode) {
    Log::timing('total');
}

try {
    $site = new Site();
    Log::timing('flush');
    $site->flushResponse();
    Log::timing('flush');
} catch (Exception $e) {
    if ($dev_mode)
        echo "\n" . '<pre>' . $e->getMessage() . "\n" . $e->getTraceAsString();
    else {
        $trace = $e->getFile() . ":" . $e->getLine() . "\n";
        $trace.='URL: '.$_SERVER['REQUEST_URI'] . "\n";
        $post = 'POST: ';
        foreach ($_POST as $f => $v) {
            $post.=$f . '=' . $v . ' ';
        }
        $trace.=$post."\n";
        $trace_ = $e->getTrace();
        foreach ($trace_ as $line) {
            $trace .= $line['file'] . ':' . $line['line'] . ' ' . $line['class'] . '::' . $line['function'] . '(' . implode(',', $line['args']) . ')' . "\n";
        }
        error_log(date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\n" . $trace . "\n", 3, '/var/log/balbum_error.log');
        throw new Exception('Fatal Exception');
    }
}
if ($dev_mode) {
    Log::timing('total');
    echo "\n" . Log::getHtmlLog();
}
