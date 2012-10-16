<?php

class Conditions {

    public $paging;
    private $perPage;
    public $currentPage;
    private $totalCount;
    //
    public $sorting;
    public $limit;
    public $filters;
    public $views;
    public $viewstitle;
    //
    private $url;
    private $pagingParameterName = 'p';
    private $defaultSortingField = '';
    private $defaultSortingOrder = '';

    function __construct() {
        
    }

    private function getCurrentPage() {
        $p = isset(Site::$get[$this->pagingParameterName]) ? Site::$get[$this->pagingParameterName] : 1;
        if ($p == 'last')
            $p = $this->getLastPage();
        $p = (int) $p;
        if ($p > $this->getLastPage())
            $p = $this->getLastPage();
        if ($p < 1)
            $p = 1;
        return $p;
    }

    private function getLastPage() {
        return ceil($this->totalCount / max(1, $this->perPage));
    }

    function getLimit() {
        if ($this->limit)
            return $this->limit;
        return (($this->currentPage - 1) * $this->perPage) . ' , ' . $this->perPage;
    }

    function setLimit($limit) {
        $this->limit = max(0, (int) $limit);
    }

    function getMongoLimit() {
        if ($this->limit)
            return 0;
        return (($this->currentPage - 1) * $this->perPage);
    }

    function getRadioFilterValue($name) {
        $v = Site::getGetField($name, false);
        if (!$v)
            return $this->defaultRadioFilter[$name];
        return $v;
    }

    function setRadioFilters($filters, $name) {
        foreach ($filters as $fname => $filter) {
            if ($filter['default'])
                $this->defaultRadioFilter[$name] = $fname;
        }

        $cur = $this->getRadioFilterValue($name);

        foreach ($filters as $fname => $filter) {
            $filter['current'] = 0;
            if (!$cur && $filter['default'])
                $filter['current'] = 1;
            if ($cur == $fname)
                $filter['current'] = 1;
            $this->filters[] = array(
                'title' => $filter['title'],
                'name' => $fname,
                'path' => $this->preparePath(array(array($name => $fname))),
                'current' => $filter['current'],
                'type' => 'radio'
            );
        }
    }

    function setFilters($filters) {
        $i = 0;
        foreach ($filters as $name => $filter) {
            $fval = $this->getFilterValue($name);
            $current_value = false;
            foreach ($filter['data'] as &$item) {
                $item['value'] = isset($item['value']) ? $item['value'] : $item['id'];

                $item['path'] = $this->preparePath(array(array('f_' . $name . '' => $item['value'])));
                $def = isset($filter['default']) ? $filter['default'] : false;
                if ($fval === false) {// no get
                    if ($def) {
                        if ($item['value'] == $def) {
                            $item['current'] = true;
                            $current_value = $def;
                        }
                    }
                }
                // no default
                // or default but get parameter exists
                // if value = get value
                if ((!$def || ($def && ($fval !== false))) && ($item['value'] == $fval)) {
                    //its current
                    $item['current'] = true;
                    $current_value = $fval;
                }
            }
            $this->filters[$i++] = array(
                'title' => $filter['title'],
                'data' => $filter['data'],
                'name' => $name,
                'current' => $current_value,
                'type' => isset($filter['type']) ? $filter['type'] : '',
                'path_clear' => $this->preparePath(array(Site::$get), 'f_' . $name),
            );
        }
    }

    function getFilters() {
        if (!$this->filters)
            return;
        $out = array();
        foreach ($this->filters as $s) {
            if ($val = Site::getGetField('f_' . $s['name'])) {
                $out[$s['name']] = $val;
            }
            if ($s['current'] !== false)
                $out[$s['name']] = $s['current'];
        }
        return $out;
    }

    function setSorting($options, $default = false, $sortingName = 'sort', $orderName = 'order') {
        $this->sortingName = $sortingName;
        $this->orderName = $orderName;
        if ($default) {
            foreach ($default as $fieldName => $data) {
                $this->defaultSortingField = $fieldName;
                if (isset($data['order'])) {
                    $this->defaultSortingOrder = $data['order'];
                }
            }
        }

        $sf = $this->getSortingField();
        $other = ($this->getSortingOrder() != 'desc') ? 'desc' : 'asc';
        foreach ($options as $name => $option) {
            $this->sorting[$name] = $option;
            if (!$this->defaultSortingField) {
                $this->defaultSortingField = $name;
                if (isset($option['order'])) {
                    $this->defaultSortingOrder = $option['order'];
                }
            }
        }
        $sf = $this->getSortingField();

        if (is_array($this->sorting))
            foreach ($this->sorting as $name => &$option) {
                $option['name'] = $name;
                if ($sf == $name) {
                    $option['current'] = 1;
                    $option['current_order'] = $this->getSortingOrder();
                    $option['path'] = $this->preparePath(array(array($sortingName => $name), array($orderName => $other)));
                } else {
                    $option['path'] = $this->preparePath(array(array($sortingName => $name), array($orderName => 'desc')));
                }
            }
    }

    function getSortingField() {
        $sf = $this->sortingName;
        $p = isset(Site::$get[$sf]) ? Site::$get[$sf] : '';
        if (!$p || !isset($this->sorting[$p])) {
            return $this->defaultSortingField;
        }
        return $p;
    }

    function getSortingOrderSQL() {
        $p = isset(Site::$get[$this->orderName]) ? Site::$get[$this->orderName] : $this->defaultSortingOrder;
        $p = ($p == 'desc') ? 'DESC' : 'ASC';
        return $p;
    }

    function setPaging($count, $perPage, $pagingParameterName = 'p') {
        $this->pagingParameterName = $pagingParameterName;
        $this->totalCount = $count;
        $this->perPage = $perPage;
        $this->currentPage = $this->getCurrentPage();
        $this->clearPaging();
        $this->addPage(1);
        for ($i = $this->currentPage - 10; $i < $this->currentPage + 10; $i++)
            $this->addPage($i);
        $this->addPage($this->getLastPage());
    }

    function getView() {
        return Site::getGetField('view', false);
    }

    function getFilterValue($name) {
        return Site::getGetField('f_' . $name, false);
    }

    function setViews($views, $xtitle = false, $anchor = false) {
        $curView = $this->getView();
        $anchorh = $anchor ? '#' . $anchor : '';

        foreach ($views as $name => $title) {

            if (!$curView)
                $curView = $name;
            if ($curView == $name)
                $this->views[] = array('anchor' => $anchor, 'name' => $name, 'title' => $title, 'path' => $this->preparePath(array(array('view' => $name))) . $anchorh, 'current' => true);
            else
                $this->views[] = array('anchor' => $anchor, 'name' => $name, 'title' => $title, 'path' => $this->preparePath(array(array('view' => $name))) . $anchorh);


            if ($title)
                $this->views['title'] = $xtitle;
        }
    }

    public function preparePath($arr, $delete_field = false, $base_url = false) {
        $path = Site::$get;
        
        $out = array();
        if ($delete_field) {
            if (!is_array($delete_field)) {
                $delete_field = array($delete_field);
            }
        }
        foreach ($arr as $i) {
            foreach ($i as $f => $v) {
                $path[$f] = $v;
                $out = array();
                foreach ($path as $f => $v) {
                    if ($f == $this->pagingParameterName) {
                        if ($v == 1) {
                            // url for first page can be without ?p=1 parameter
                        }else
                            $out[] = $f . '=' . $v;
                    }else
                    if ($delete_field && (in_array($f, $delete_field))) {

                    }
                    else
                        $out[] = $f . '=' . $v;
                }
            }
        }
        $path = implode('&', $out);
        
        if ($path)
            return ($base_url ? $base_url : Config::need('www_path').Site::$request_uri) . '?' . $path;
        else
            return ($base_url ? $base_url : Config::need('www_path').Site::$request_uri);
    }

    private function getSortingOrder() {
        $p = isset(Site::$get[$this->orderName]) ? Site::$get[$this->orderName] : $this->defaultSortingOrder;
        $p = ($p == 'asc') ? 'asc' : 'desc';
        return $p;
    }

    function clearPaging() {
        $this->paging = array();
    }

    private function addPage($id) {
        if (($id = (int) $id) < 1)
            return;
        if ($id > $this->getLastPage())
            return;
        $this->paging[$id] = array(
            'title' => $id,
            'path' => $this->preparePath(array(array($this->pagingParameterName => $id))),
        );
        if ($id == $this->getLastPage())
            $this->paging[$id]['last'] = 1;
        if ($id == 1)
            $this->paging[$id]['first'] = 1;

        if ($id == $this->getCurrentPage() + 1)
            $this->paging[$id]['next'] = 1;

        if ($id == $this->getCurrentPage() - 1)
            $this->paging[$id]['prev'] = 1;

        if ($id == $this->getCurrentPage())
            $this->paging[$id]['current'] = 1;
    }

    function getConditions() {
        $out = array();
        if (count($this->paging) > 1)
            $out[] = array('mode' => 'paging', 'options' => $this->paging);
        if ($this->sorting)
            $out[] = array('mode' => 'sorting', 'options' => array_values($this->sorting));
        if ($this->filters) {
            $temp_filters = array();
            $temp_cb = array();
            $temp_radio = array();
            // чекбоксы отдельно
            foreach ($this->filters as $filter) {
                if (isset($filter['type']) && $filter['type'] == 'checkbox') {
                    $temp_cb[] = $filter;
                } else
                if (isset($filter['type']) && $filter['type'] == 'radio') {
                    $temp_radio[] = $filter;
                }
                else
                    $temp_filters[] = $filter;
            }
            if (count($temp_filters))
                $out[] = array('mode' => 'filter', 'options' => $temp_filters);
            if (count($temp_cb))
                $out[] = array('mode' => 'checkbox', 'options' => $temp_cb);
            if (count($temp_radio))
                $out[] = array('mode' => 'radio', 'options' => $temp_radio);
        }

        if ($this->views)
            $out[] = array('mode' => 'view', 'options' => $this->views);
        return $out;
    }

}