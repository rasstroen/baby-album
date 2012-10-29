<?php

/**
 *
 * @author mchubar
 */
class MailSender {

    function mail($sFrom, $sTo, $sSubject, $sBody) {    //Послать почту
        require_once('Mail.php');

        $aHeaders = array(
            'From' => $sFrom,
            'To' => $sTo,
            'Subject' => '=?UTF-8?B?' . base64_encode($sSubject) . '?=',
            'Mime-Version' => '1.0',
            'Content-Type' => 'text/html; charset=utf-8'
        );
        $smtp = Mail::factory(
                        'smtp', array(
                    'host' => 'ssl://smtp.gmail.com',
                    'port' => '465',
                    'auth' => true,
                    'username' => Config::GLOBAL_EMAIL,
                    'password' => Config::GLOBAL_EMAIL_PWD
                        )
        );

        $mail = $smtp->send($sTo, $aHeaders, $sBody);
        return !(PEAR::isError($mail));
    }

}