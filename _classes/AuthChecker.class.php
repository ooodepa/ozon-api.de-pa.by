<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];
        
include_once "$HOME/env.php";

class AuthChecker {
    static function isAuth() {
        global $env;

        if (!isset($_COOKIE['auth-id'])) {
            return false;
        }

        $isEquals = strcmp($_COOKIE['auth-id'], $env['auth-id']) == 0;
        return $isEquals;
    }
}
