<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];
    
include_once "$HOME/api/finance-transaction-list/FinanseTransactionList.class.php";

class FinanceTransactionListServiceNames {
    static function getUniqServiceNameArray() {
        $transactionArray = FinanseTransactionList::getTransactions();

        $uniqServiceNameArray = [];

        for ($i = 0; $i < count($transactionArray); $i++) {
            $current = $transactionArray[$i];
            $services = [];
            if (isset($current['services'])) {
                $services = $current['services'];
            }

            for ($j = 0; $j < count($services); $j++) {
                $curren_service_name = $services[$j]['name'];
                if (!in_array($curren_service_name, $uniqServiceNameArray)) {
                    array_push($uniqServiceNameArray, $curren_service_name);
                }
            }
        }

        return $uniqServiceNameArray;
    }
}
