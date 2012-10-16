<?php

class Site {

    public static $request_uri;
    public static $request_uri_array;
    public static $get;
    public static $config;
    public static $responseData;
    public static $responseHtml;
    private static $openedModules;
    private static $writePass;
    public static $lang = 'ru';

    function __construct() {
        CurrentUser::authorize_cookie();
        // разбираем запрос
        Log::timing('request');
        $this->processRequest();
        Log::timing('request');
        // пишем модулями записи
        Log::timing('write');
        $this->processWrite();
        Log::timing('write');
        // ищем конфигурацию страницы для выдачи юзеру
        Log::timing('prepare config');
        $this->preparePageConfiguration();
        Log::timing('prepare config');
        // тянем данные
        $this->executeModules();
        // подключаем шаблонизатор
        $this->applyTemplates();
    }

    public static function passWrite($f, $v = false) {
        self::$writePass[$f] = $v;
    }

    public static function passTitle($title) {
        self::$config['title'] = $title;
    }

    public static function passKeywords($keywords) {
        self::$config['keywords'] = $keywords;
    }

    public static function passDescription($description) {
        self::$config['description'] = $description;
    }

    function processWrite() {
        if (isset($_POST['writemodule'])) {
            $wmClass = $_POST['writemodule'] . '_write';
            $writemodule = new $wmClass;
            $writemodule->process();
        }
    }

    public static function setTitle($title = '') {
        self::$config['title'] = htmlspecialchars(trim($title));
    }

    function applyTemplates() {
        ob_start();

        global $data;
        global $config;
        global $write;
        global $uri;
        $data = self::$responseData;
        $config = self::$config;
        $write = self::$writePass;
        $uri = array_keys(self::$request_uri_array);


        $filename = Config::need('templates_root') . DIRECTORY_SEPARATOR . self::$config['layout'] . '.php';
        if (file_exists($filename))
            require $filename;
        else
            throw new Exception('no template [' . self::$config['layout'] . '] found');
        self::$responseHtml = ob_get_clean();
    }

    function preparePageConfiguration() {
        self::$config = Map::getPageConfiguration(array_values(self::$request_uri_array));
    }

    function executeModules() {
        if (self::$config && isset(self::$config['blocks'])) {
            foreach (self::$config['blocks'] as $block => $modules) {
                foreach ($modules as $data) {
                    Log::timing('module open ' . $data['name'] . '/' . $data['action'] . '/' . $data['mode']);
                    $module = $this->getOpenedModule($data['name']);
                    Log::timing('module open ' . $data['name'] . '/' . $data['action'] . '/' . $data['mode']);
                    Log::timing('module ' . $data['name'] . '/' . $data['action'] . '/' . $data['mode']);
                    $result = $module->process($data['action'], $data['mode']);
                    self::$responseData[$block][] = array(
                        'module' => $data['name'],
                        'action' => $data['action'],
                        'mode' => $data['mode'],
                        'result' => $result,
                    );
                    Log::timing('module ' . $data['name'] . '/' . $data['action'] . '/' . $data['mode']);
                }
            }
        }
    }

    function execCabinetQuery() {
        $c = new CabinetQuery();
        die(json_encode($c->process($_POST)));
    }

    function execAjaxQuery() {
        $data = array(
            'method' => $method = $_POST['method'],
        );
        new AjaxQuery($method, $_POST, $data);
        die(json_encode($data));
    }

    function getOpenedModule($name) {
        $name = 'module_' . $name;
        if (!isset(self::$openedModules[$name])) {
            Log::timing('module new ' . $name);
            self::$openedModules[$name] = new $name;
            Log::timing('module new ' . $name);
        }
        return self::$openedModules[$name];
    }

    function processRequest() {
        if (isset($_POST['method'])) {
            return $this->execAjaxQuery();
        }

        $www_folder = Config::need('www_folder', '');
        $request_uri = explode('?', $_SERVER['REQUEST_URI']);
        $get_params_string = isset($request_uri[1]) ? $request_uri[1] : '';
        $get_params = array();
        parse_str($get_params_string, $get_params);
        self::$get = $get_params;

        $request_uri = $request_uri[0];
        $request_uri = explode('/', $request_uri);
        $request_uri_array = array();
        foreach ($request_uri as $uripart) {
            $trimmed = trim($uripart);
            if (trim($uripart) !== '')
                $request_uri_array[$trimmed] = $trimmed;
        }

        $cleared_request_uri = implode('/', $request_uri_array);
        if ($www_folder && strpos($cleared_request_uri, $www_folder) === 0) {
            $cleared_request_uri = substr($cleared_request_uri, strlen($www_folder));
            array_shift($request_uri_array);
        }

        self::$request_uri = $cleared_request_uri;
        self::$request_uri_array = $request_uri_array;
    }

    function flushResponse() {
        echo self::$responseHtml;
    }

}