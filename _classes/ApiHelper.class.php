<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

include_once "$HOME/_classes/AuthChecker.class.php";

class ApiHelper {
    static function echoJson_AndExist($params) {
        $http_status_code = 200;
        if (isset($params['status'])) {
            $http_status_code = $params['status'];
        }

        $php_object = [];
        if (isset($params['data'])) {
            $php_object = $params['data'];
        }

        $string_json = json_encode($php_object, JSON_UNESCAPED_UNICODE);

        http_response_code($http_status_code);
        header('Content-Type: application/json; charset=utf-8');
        echo $string_json;
        exit;
    }

    static function echoExceptionJson_andExit($exception) {
        ApiHelper::echoJson_AndExist([
            'status' => 500,
            'data' => [
                'message' => "$exception",
            ],
        ]);
    }

    static function ifNotAuth_echoNotAuthJson_andExit() {
        if (AuthChecker::isAuth()) {
            return;
        }

        ApiHelper::echoJson_AndExist([
            'status' => 401,
            'data' => [
                'message' => "Вы не авторизованы",
            ],
        ]);
    }
}
