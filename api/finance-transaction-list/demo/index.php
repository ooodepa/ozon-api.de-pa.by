<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Уникальный список заказов</title>
    <link rel="stylesheet" href="/assets/npm/bootstrap/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-2 mb-2">
        <div id="root">
            Загрузка
        </div>
    </div>

    <script src="/api/finance-transaction-list/demo/js/FinanseTransactionList.class.js"></script>
    <script>
        try {
            FinanseTransactionList.innerHtml();
        }
        catch(exception) {
            console.error(exception);
        }
    </script>
</body>
</html>
