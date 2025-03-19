<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];
    
include_once "$HOME/env.php";

class FinanseTransactionList {
    static function getTransactions() {
        $startDate = '2024-01-10';
        $endDate = (new DateTime('tomorrow'))->format('Y-m-d');

        $result = FinanseTransactionList::getData($startDate, $endDate);

        $resultArray = [];
        for ($i = 0; $i < count($result); $i++) {
            $data = FinanseTransactionList::getTransactions_OnPeriod($result[$i]['start'], $result[$i]['end']);
            $arr = $data['result']['operations'];
            for ($j = 0; $j < count($arr); $j++) {
                $resultArray []= $arr[$j];
            }
        }

        return $resultArray;
    }

    static function getData($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('last day of this month'); // Получаем последний день месяца для конечной даты
        $data = [];
    
        while ($start <= $end) {
            $startOfMonth = $start->format('Y-m-01\T00:00:00.000\Z');
            $endOfMonth = $start->format('Y-m-t\T23:59:59.999\Z');
    
            $data[] = [
                'start' => $startOfMonth,
                'end' => $endOfMonth
            ];
    
            $start->modify('first day of next month'); // Переход к следующему месяцу
        }
    
        return $data;
    }

    static function getTransactions_OnPeriod($dateFrom, $dateTo) {        
        return FinanseTransactionList::getFinanceTransactions_byData([
            "filter" => [
                "date" => [
                    "from" => $dateFrom,
                    "to" => $dateTo,
                ],
                "operation_type" => [],
                "posting_number" => "",
                "transaction_type" => "all",
            ],
            "page" => 1,
            "page_size" => 1000,
        ]);
    }

    static function getFinanceTransactions_byData($HTTP_DATA) {
        global $env;
        $URI = "/v3/finance/transaction/list";
        $FETCH_URL = "https://api-seller.ozon.ru$URI";

        $jsonData = json_encode($HTTP_DATA);

        $ozonClientId = $env['ozon-client-id'];
        $ozonApiKey = $env['ozon-api-key'];

        $http_header_array = [
            "Content-Type: application/json",
            "Client-Id: $ozonClientId",
            "Api-Key: $ozonApiKey",
        ];

        $http_cookie_string = implode("; ", [
            "Client-Id=$ozonClientId",
            "Api-Key=$ozonApiKey",
        ]);

        $ch = curl_init($FETCH_URL);                                    // Инициализируем cURL сессии
        try {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);                       // Устанавливаем метод POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);            // Тело запроса
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header_array);   // Устанавливаем заголовки
            //curl_setopt($ch, CURLOPT_COOKIE, $http_cookie_string);    // Передаем куки
            $response = curl_exec($ch);                                 // Выполняем запрос и получаем ответ
    
            if (curl_errno($ch)) {                                      // Проверяем на наличие ошибок
                $err = curl_error($ch);
                throw new Error("Fetch error: $err");
            }

            $string_json = $response;
            $php_object = json_decode($string_json, true);
            return $php_object;
        }
        catch(Throwable $exception) {
            return [];
        }
        finally {
            curl_close($ch);                                            // Закрываем cURL сессию
        }
    }
}
