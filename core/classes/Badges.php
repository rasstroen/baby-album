<?php

/**
 *
 * @author mchubar
 */
class Badges {

    const ACTION_TYPE_LOGIN = 10;
    const ACTION_TYPE_ADD_EVENT = 20;
    const ACTION_TYPE_ADD_THEMED_EVENT = 30;
    const ACTION_TYPE_ADD_PHOTO = 40;
    const ACTION_TYPE_EDIT_PROFILE = 50;
    const ACTION_TYPE_LIKE = 60;
    const ACTION_TYPE_LIKED = 70;
    const ACTION_TYPE_COMMENT = 80;
    const ACTION_TYPE_COMMENTED = 90;

    private static $badges = array(
    );

    public static function progressAction($user_id, $action_type, $params) {
        
    }

    public static function getUserBadges($user_id) {
        
    }

    public static function getUserPoints($user_id) {
        
    }

    public static function getUserAllBadges($user_id) {
        
    }

    private static function addPoints($user_id, $points_count, $message) {
        
    }

    private static function decPoints($user_id, $points_count, $message) {

    }

    private static function addBadge($user_id, $badge_id) {
        
    }

}