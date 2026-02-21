<?php
namespace PineappleFinance\Pages\BankAccount;

if ( !isset($_GET['account-code']) ) {
    header("Location: /pages/accounts.php");
    exit();
}
$account_code = htmlspecialchars($_GET['account-code']);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "account_number" => $_GET['account-code'],
        "account_description" => $_POST['account_description'],
        "account_balance" => $_POST['account_balance'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $bankAccountService->GetSanitisedInput($input);

    if ($sanitisedInput) {
        $response = $bankAccountService->UpdateBankAccount($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);

$bankAccount = $bankAccountService->GetBankAccount($account_code)[0];
// print_r($bankAccount);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Banks | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Pineapple Finance Site" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="/css/normalize.css" />
  <link rel="stylesheet" href="/css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
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

            <h1>Update Bank Account</h1>

            <form method="post">
<!-- 
                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <select class="u-full-width" id="selectBankCode" name="bank_code"
                            value="<?php echo isset($bankAccount['bank_id']) ? $bankAccount['bank_id'] : ''; ?>">
                            <option value="">Select Bank</option>
                            <?php foreach ($bankIdNamePairList as $idNamePair) {?>
                            <option value="<?php echo $idNamePair['id']; ?>"
                            <?php if (isset($bankAccount['name']) && $bankAccount['name'] == $idNamePair['name']) { echo " selected"; } ?>
                            ><?php echo $idNamePair['name']; ?>
                        </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountNumber">Account Number</label>
                        <input class="u-full-width" type="text" placeholder="Account number" id="inputAccountNumber" name="account_number" 
                            value="<?php echo isset($bankAccount['account_code']) ? $bankAccount['account_code'] : ''; ?>"
                        />
                    </div>
                </div>
-->

                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <input class="u-full-width" type="text" placeholder="Bank code" id="inputBankCode" name="bank_code" readonly
                            value="<?php echo isset($bankAccount['name']) ? $bankAccount['name'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountNumber">Account Number</label>
                        <input class="u-full-width" type="text" placeholder="Account number" id="inputAccountNumber" name="account_number" readonly
                            value="<?php echo isset($bankAccount['account_code']) ? $bankAccount['account_code'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountDescription">Account Description</label>
                        <input class="u-full-width" type="text" placeholder="Account description" id="inputAccountDescription" name="account_description" 
                            value="<?php echo isset($bankAccount['description']) ? $bankAccount['description'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputAccountBalance">Account Balance</label>
                        <input class="u-full-width" type="text" placeholder="Account balance" id="inputAccountBalance" name="account_balance"
                            value="<?php echo isset($bankAccount['balance']) ? $bankAccount['balance'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Bank Account">
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
