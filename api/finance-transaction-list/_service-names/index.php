<?php

try {
    $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];
        
    include_once "$HOME/_classes/ApiHelper.class.php";
    include_once "$HOME/_classes/AuthChecker.class.php";
    include_once "$HOME/api/finance-transaction-list/_service-names/FinanceTransactionListServiceNames.class.php";

    try {
        ApiHelper::ifNotAuth_echoNotAuthJson_andExit();
    
        $PHP_OBJECT = FinanceTransactionListServiceNames::getUniqServiceNameArray();

        ApiHelper::echoJson_andExist([
            'status' => 200,
            'data' => $PHP_OBJECT,
        ]);
    }
    catch(Throwable $exception) {
        ApiHelper::echoExceptionJson_andExist($exception);
    }
}
catch(Throwable $exception) {
    http_response_code(500);
    echo "<pre style='color: red;'>";
    print_r($exception);
    echo "</pre>";
}
