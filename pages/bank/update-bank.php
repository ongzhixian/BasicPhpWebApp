<?php
namespace PineappleFinance\Pages\Bank;

if ( !isset($_GET['code']) ) {
    header("Location: /pages/bank/index.php");
    exit();
}
$bankCode = htmlspecialchars($_GET['code']);

session_start();

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

$bank = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank_code = $_POST['bank_code'];
    $bank_full_name = $_POST['bank_full_name'];

    $response = $bankService->UpdateBank($bank_code, $bank_full_name);
    $feedbackMessage = $response["message"];
}
$bank = $bankService->GetBank($bankCode)[0];

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

    <?php include '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <nav>
                <!-- 
                <a href="/pages/register-bank.php" class="button button-primary">Register bank</a>
                <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <form method="post">
                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <input class="u-full-width" type="text" placeholder="Bank code" id="inputBankCode" readonly="readonly" 
                        name="bank_code" value="<?php echo isset($bank['name']) ? $bank['name'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputBankFullName">Bank Full Name</label>
                        <input class="u-full-width" type="text" placeholder="Bank full name" id="inputBankFullName" name="bank_full_name" 
                        value="<?php echo isset($bank['full_name']) ? $bank['full_name'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Bank" />
                    <span class="feedback-message"><?php echo isset($feedbackMessage) ? $feedbackMessage : ''; ?></span>
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
