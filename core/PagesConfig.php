<?php

/**
 * @author mchubar
 */
class PagesConfig {

    private static $pages = array(
        '404' => array(),
        // Главная
        'admin_template_edit' => array(
            'title' => 'Baby Album Admin Template Edit',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'admin', 'action' => 'edit', 'mode' => 'template'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'admin_event_edit' => array(
            'title' => 'Baby Album Admin Event Edit',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'admin', 'action' => 'edit', 'mode' => 'event'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'show_event' => array(
            'title' => 'Baby Album One Event',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'album', 'action' => 'show', 'mode' => 'event'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'user_profile_edit' => array(
            'title' => 'Baby Album User Editing',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'user', 'action' => 'edit', 'mode' => 'profile'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'edit_event' => array(
            'title' => 'Baby Album Edit Event',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'album', 'action' => 'edit', 'mode' => 'event'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'user_profile' => array(
            'title' => 'Baby Album User',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'user', 'action' => 'show', 'mode' => 'profile'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'album' => array(
            'title' => 'Baby Album Album',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'album', 'action' => 'list', 'mode' => 'events'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'admin_templates' => array(
            'title' => 'Baby Album Admin',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'admin', 'action' => 'list', 'mode' => 'templates'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'admin' => array(
            'title' => 'Baby Album Admin',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'admin', 'action' => 'list', 'mode' => 'events'),
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'index' => array(
            'title' => 'Baby Album',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                ),
                'sidebar' => array(
                ),
            ),
        ),
        'publication' => array(
            'title' => 'Публикация',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'publication', 'action' => 'show', 'mode' => 'item'),
                ),
            ),
        ),
        'publications' => array(
            'title' => 'Публикации',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'publication', 'action' => 'list', 'mode' => 'main'),
                ),
            ),
        ),
        'register' => array(
            'title' => 'Регистрация',
            'layout' => 'two_columns',
            'css' => array(
            ),
            'js' => array(
            ),
            'blocks' => array(
                'content' => array(
                    array('name' => 'user', 'action' => 'show', 'mode' => 'register'),
                ),
            ),
        ),
    );
    private static $pages_default = array(
        'two_columns' => array(
            'js' => array(
                array('href' => '/static/js/jquery.min.js'),
                array('href' => '/static/js/jquery.timeago.js'),
            ),
            'css' => array(
                array('href' => '/static/css/style.css'),
            ),
            'blocks' => array(
                'header' => array(
                    array('name' => 'user', 'action' => 'show', 'mode' => 'top_menu'),
                    array('name' => 'user', 'action' => 'show', 'mode' => 'static_auth'),
                )
            ),
        ),
    );

    public static function get($page_name) {
        global $dev_mode;
        if ($dev_mode)
            $timebreaker = floor(time() / 60 / 10);
        else
            $timebreaker = Config::need('config_version', 1);
        $config = array();
        $p404 = false;
        if (isset(self::$pages[$page_name])) {
            $config = self::$pages[$page_name];
        } else {
            $config = self::$pages['404'];
            $p404 = true;
        }
        // apply default
        $wf = Config::need('www_folder');
        $root = $wf ? '/' . Config::need('www_folder') : '';
        if (!$p404) {
            //blocks
            if (isset(self::$pages_default[$config['layout']]['blocks'])) {
                foreach (self::$pages_default[$config['layout']]['blocks'] as $blockname => $modules) {
                    foreach ($modules as $module) {
                        $config['blocks'][$blockname][] = $module;
                    }
                }
            }
            $tcss = array();
            if (isset(self::$pages_default[$config['layout']]['css'])) {
                foreach (self::$pages_default[$config['layout']]['css'] as &$css) {
                    $css['href'] = $root . $css['href'];
                    $css['href'].='?' . $timebreaker;

                    $tcss[] = $css;
                }
            }
            $tjs = array();
            if (isset(self::$pages_default[$config['layout']]['js'])) {
                foreach (self::$pages_default[$config['layout']]['js'] as $js) {
                    $js['href'] = $root . $js['href'];

                    $js['href'].='?' . $timebreaker;

                    $tjs [] = $js;
                }
            }

            foreach ($config['js'] as &$js) {
                $js['href'] = $root . $js['href'];

                $js['href'].='?' . $timebreaker;
                $tjs [] = $js;
            }


            foreach ($config['css'] as &$css) {
                $css['href'] = $root . $css['href'];

                $css['href'].='?' . $timebreaker;
                $tcss [] = $css;
            }

            $config['js'] = $tjs;
            $config['css'] = $tcss;
        }
        return $config;
    }

}