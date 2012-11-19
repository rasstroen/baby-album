<?php

/**
 * @author mchubar
 */
class Map {

    public static $map = array(
        'index' => array(
            '' => 'index',
        ),
        'event' => array(
            '%d' => array(
                '' => 'list_of_event', // все эвенты данного типа
            ),
        ),
        'forget' => array(
            '' => 'forget',
        ),
        'register' => array(
            '' => 'register',
        ),
        'faq' => array(
            '%s' => 'faq',
        ),
        'agreement' => array(
            '' => 'agreement',
        ),
        'c' => array(
            '%s' => 'email_confirmation',
        ),
        'f' => array(
            '%s' => 'pass_restore',
        ),
        'albums' => array(
            '' => 'feed_albums',
        ),
        'album' => array(
            '%d' => array(
                'suggested_events' => 'album_suggested_events', // подсказки для конкретного альбома
                'edit' => 'album_edit',
                'rel_accept' => 'album_rel_accept',
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
                'edit' => 'user_profile_edit',
                'points' => array(
                    '' => 'user_points'
                ),
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
        if (!$page_name){
            header('404 Not Found',1,404);
            return;
        }

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