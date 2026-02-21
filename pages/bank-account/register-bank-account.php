<?php
namespace PineappleFinance\Pages\BankAccount;

require_once "../../_config.php"; // NOSONAR: Manual auto-loader
use PineappleFinance\Includes\DefaultPageHeader;

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

require_once "../../modules/bank_account_service.php";
use PineappleFinance\Services\BankAccountService;
$bankAccountService = new BankAccountService();

session_start();
$session_user_id = require_authenticated_user();

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "bank_code" => $_POST['bank_code'],
        "account_number" => $_POST['account_number'],
        "account_description" => $_POST['account_description'],
        "account_balance" => $_POST['account_balance'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $bankAccountService->GetSanitisedInput($input, true);

    if ($sanitisedInput) {
        $response = $bankAccountService->RegisterBankAccount($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Bank Account | Pine</title>
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

    <?php DefaultPageHeader::render(); ?>

    <article class="with-aside">

        <main>

            <nav>
                <!-- 
                <a href="/pages/register-bank.php" class="button button-primary">Register bank</a>
                <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <h1>Register Bank Account</h1>

            <form method="post">
                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <select class="u-full-width" id="selectBankCode" name="bank_code" value="">
                            <option value="">Select Bank</option>
                            <?php foreach ($bankIdNamePairList as $idNamePair) {?>
                            <option value="<?php echo $idNamePair['id']; ?>"><?php echo $idNamePair['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountNumber">Account Number</label>
                        <input class="u-full-width" type="text" placeholder="Account number" id="inputAccountNumber" name="account_number" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountDescription">Account Description</label>
                        <input class="u-full-width" type="text" placeholder="Account description" id="inputAccountDescription" name="account_description" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountBalance">Account Balance</label>
                        <input class="u-full-width" type="text" placeholder="Account balance" id="inputAccountBalance" name="account_balance" value="0" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Register Bank Account">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

                <!--
                <div class="row">
                    <div class="four columns">
                        <label for="exampleRecipientInput">Reason for contacting</label>
                        <select class="u-full-width" id="exampleRecipientInput">
                            <option value="Option 1">Questions</option>
                            <option value="Option 2">Admiration</option>
                            <option value="Option 3">Can I get your number?</option>
                        </select>
                    </div>
                </div>
                -->

            </form>


        </main>

        <aside>
            <h1>Banks</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
