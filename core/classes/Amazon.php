<?php

require_once 'amazon/sdk.class.php';

class Amazon {

    /**
     *  @var S3 $s3
     */
    private static $bucket_name = 'balbum';
    private static $access_key = 'AKIAJXZGEV24XAFW6IUQ';
    private static $secret_key = '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';
    private static $endpoint = 's3-website-eu-west-1.amazonaws.com';
    /* @property $s3 S3 */
    public static $s3 = false;

    private static function init() {
        if (self::$s3)
            return true;
        self::$s3 = new AmazonS3(array(
                    'key' => self::$access_key,
                    'secret' => self::$secret_key
                ));
    }

    public static function store($source, $dest) {
        self::init();
        $s3 = self::$s3;
        /* @var $s3 AmazonS3 */
        $result = $s3->create_object(self::$bucket_name, $dest, array(
            'fileUpload' => $source,
            'acl' => AmazonS3::ACL_PUBLIC
                ));
        if ($result->status == 200)
            return true;
        return false;
    }

}