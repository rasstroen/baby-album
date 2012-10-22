<?php

/**
 *
 * @author mchubar
 */
class module_publication extends module {

    function _process($action, $mode) {
        switch ($action) {
            case 'list':
                switch ($mode) {
                    case 'main':
                        return $this->listMain();
                        break;
                }
                break;
            case 'show':
                switch ($mode) {
                    case 'item':
                        return $this->showItem();
                        break;
                }
                break;
        }
    }

    function showItem() {
        $publication_id = array_values(Site::$request_uri_array);
        $publication_id = (int) $publication_id[1];
        $data = $this->_list(array('where' => array('P.id=' . $publication_id)));
        Site::passTitle($data['publications'][$publication_id]['title']);
        Site::passKeywords($data['publications'][$publication_id]['keywords']);
        return $data;
    }

    function listMain() {
        return $this->_list();
    }

    function _list($opts = array()) {

        $has_paging = !isset($opts['no_paging']);
        $show_sortings = isset($opts['show_sortings']);
        $per_page = isset($opts['per_page']) ? $opts['per_page'] : 10;
        $per_page = min(100, max(1, (int) $per_page));

        $cond = new Conditions();
        $cond->setSorting(array('created' => array('order' => 'desc', 'title' => 'по дате')));
        $cond->setPaging(100000, $per_page);

        $where = array('1');
        if (isset($opts['where']))
            foreach ($opts['where'] as $w)
                $where[] = $w;
        $order = $cond->getSortingField() . ' ' . $cond->getSortingOrderSQL();
        $limit = $cond->getLimit();

        $query = 'SELECT SQL_CALC_FOUND_ROWS P. * , GROUP_CONCAT( T.title ) AS tags, GROUP_CONCAT( PT.tag_id ) AS tags_indexes
FROM `publications` P
LEFT JOIN `publications_tags` PT ON PT.publication_id = P.id
LEFT JOIN `tags` T ON T.id = PT.tag_id
WHERE (' . implode(' AND ', $where) . ')
GROUP BY P.id
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $publications = Database::sql2array($query, 'id');
        foreach ($publications as $publication) {
            $uids[$publication['user_id']] = $publication['user_id'];
        }

        $users = Users::getByIdsLoaded($uids);
        foreach ($publications as &$publication) {
            $publication['user'] = isset($users[$publication['user_id']]) ? $users[$publication['user_id']]->data : array();
        }

        $cond->setPaging(Database::sql2single('SELECT FOUND_ROWS()'), $per_page);

        $data['publications'] = $publications;
        $data['conditions'] = $cond->getConditions();
        if (!$show_sortings) {
            foreach ($data['conditions'] as $key => $group) {
                if ($group['mode'] == 'sorting') {
                    unset($data['conditions'][$key]);
                }
            }
        }

        return $data;
    }

}