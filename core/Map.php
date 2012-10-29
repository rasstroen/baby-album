<?php

/**
 * @author mchubar
 */
class Map {
    /*
      главная - топ фоток, меню авт/меню не авт
      мой профиль
      фид
     *
     */

    public static $map = array(
        'index' => array(
            '' => 'index',
        ),
        'register' => array(
            '' => 'register',
        ),
        'c' => array(
            '%s' => 'email_confirmation',
        ),
        'albums' => array(
            '' => 'feed_albums',
        ),
        'album' => array(
            '%d' => array(
                'suggested_events' => 'album_suggested_events',
                'edit' => 'album_edit',
                '' => 'album',
                'event' => array(
                    '%d' => array(
                        '' => 'show_event',
                        'edit' => 'edit_event',
                    ),
                    'new' => 'edit_event'
                )
            ),
            'new' => 'album_edit',
        ),
        'publication' => array(
            '' => 'publications', // публикации
            '%d' => array(
                '' => 'publication', // публикация
                'edit' => 'publication_edit' // редактирование публикации
            )
        ),
        'u' => array(
            '%s' => array(
                '' => 'user_profile',
                'edit' => 'user_profile_edit'
            )
        ),
        'admin' => array(
            '' => 'admin',
            'templates' => array(
                '' => 'admin_templates',
                '%d' => array(
                    'edit' => 'admin_template_edit'
                ),
            ),
            'event' => array(
                '%d' => array(
                    'edit' => 'admin_event_edit',
                )
            )
        ),
    );

    public static function getPageConfiguration(array $url_array) {
        $url_array = count($url_array) ? $url_array : array('index');
        $page_name = false;
        foreach (Map::$map as $page => $subparams) {
            if ($url_array[0] === $page) {
                $page_name = Map::getSubpageConfiguration($url_array, $subparams);
            }
        }
        if (!$page_name)
            throw new Exception('no route for ' . implode('/', $url_array));

        $config = PagesConfig::get($page_name);
        if (!count($config))
            throw new Exception('no configuration for route [' . $page_name . ']');
        return $config;
    }

    private static function getSubpageConfiguration($url_array, $subparams, $max_page_name = '') {

        if (!is_array($subparams))
            return $subparams;

        array_shift($url_array);

        $next_url_part = isset($url_array[0]) ? $url_array[0] : '';
        foreach ($subparams as $page => $subparams_) {
            if ($page === '')
                $max_page_name = $subparams_;
            if ($next_url_part === $page) {
                return Map::getSubpageConfiguration($url_array, $subparams_, $max_page_name);
            }
        }

        if (is_numeric($next_url_part)) {
            foreach ($subparams as $page => $subparams_) {
                if ('%d' === $page) {
                    return Map::getSubpageConfiguration($url_array, $subparams_, $max_page_name);
                }
            }
        }

        foreach ($subparams as $page => $subparams_) {
            if ('%s' === $page) {
                return Map::getSubpageConfiguration($url_array, $subparams_, $max_page_name);
            }
        }

        return $max_page_name;
    }

}