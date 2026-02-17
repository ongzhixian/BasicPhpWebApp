<?php
namespace PineappleFinance\Pages\Investment;

if ( !isset($_GET['id']) ) {
    header("Location: /pages/investment/index.php");
    exit();
}
$equity_id = htmlspecialchars($_GET['id']);

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

require_once "../../modules/equity_service.php";
use PineappleFinance\Services\EquityService;
$equityService = new EquityService();

session_start();
$session_user_id = require_authenticated_user();

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = (object) [
        "id" => $_GET['id'],
        "symbol" => $_POST['symbol'],
        "description" => $_POST['description'],
        "quantity" => $_POST['quantity'],
        "buy_price" => $_POST['buy_price'],
        "buy_date" => $_POST['buy_date'],
        "current_price" => $_POST['current_price'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $equityService->GetSanitisedInput($input, false);

    if ($sanitisedInput) {
        $response = $equityService->UpdateEquity($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);
$equity = $equityService->GetEquity($equity_id)[0];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Equity | Pine</title>
    <meta charset="utf-8" />
    <meta name="description" content="Pineapple Finance Site" />
    <meta name="author" content="Ong Zhi Xian" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/normalize.css" />
    <link rel="stylesheet" href="/css/skeleton.css" />
    <link rel="stylesheet" href="/css/site.css" />
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <h1>Update Equity</h1>

            <form method="post">
                

<!--
[symbol] [varchar](12) NOT NULL,
[description] [varchar](128) NOT NULL,
[quantity] [decimal](18, 2) NOT NULL,
[buy_price] [decimal](18, 2) NOT NULL,
[buy_date] [datetime2](7) NOT NULL,
[current_price] [decimal](18, 2) NOT NULL,
-->

                <div class="row">
                    <div class="four columns">
                        <label for="inputSymbol">Symbol</label>
                        <input class="u-full-width" type="text" placeholder="Symbol" id="inputSymbol" name="symbol"
                            value="<?php echo isset($equity['symbol']) ? $equity['symbol'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Descriptive text" id="inputDescription" name="description" 
                            value="<?php echo isset($equity['description']) ? $equity['description'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputQuantity">Quantity</label>
                        <input class="u-full-width" type="text" placeholder="Quantity" id="inputQuantity" name="quantity" 
                            value="<?php echo isset($equity['quantity']) ? $equity['quantity'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputBuyPrice">Buy Price</label>
                        <input class="u-full-width" type="text" placeholder="Buy price" id="inputBuyPrice" name="buy_price" 
                            value="<?php echo isset($equity['buy_price']) ? $equity['buy_price'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputBuyDate">Buy Date</label>
                        <input class="u-full-width" type="text" placeholder="Buy date" id="inputBuyDate" name="buy_date"
                            value="<?php echo isset($equity['buy_date']) ? $equity['buy_date']->format('Y-m-d') : ''; ?>" />
                    </div>
                </div>


                <div class="row">
                    <div class="four columns">
                        <label for="inputCurrentPrice">Current Price</label>
                        <input class="u-full-width" type="text" placeholder="Current price" id="inputCurrentPrice" name="current_price" 
                            value="<?php echo isset($equity['current_price']) ? $equity['current_price'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Equity">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Investments</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
