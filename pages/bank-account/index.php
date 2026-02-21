<?php
namespace PineappleFinance\Pages\BankAccount;

require_once "../../_config.php";

require_once "../../modules/bank_account_service.php";
use PineappleFinance\Services\BankAccountService;
$bankAccountService = new BankAccountService();

session_start();
require_authenticated_user();

$bankAccountList = $bankAccountService->GetBankAccountList($_SESSION['user_id']);
$totalBankBalance = $bankAccountService->GetTotalBankBalance($_SESSION['user_id'])[0]['balance'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bank Accounts | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Bank account list" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="/css/normalize.css" />
  <link rel="stylesheet" href="/css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  <link rel="stylesheet" href="/css/site.css" />
</head>
<body>

    <?php include_once '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

        <!--
            <nav>
                <a href="/pages/accounts.php" class="button-primary">Accounts</a>
                <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a>
            </nav>
        -->

            <nav>
                <a href="/pages/bank-account/register-bank-account.php" class="button button-primary">Register bank account</a>
                <!-- <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>


            <table class="u-full-width accounts">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Account Number</th>
                        <th>Balance</th>
                        <th>Account Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bankAccountList as $bankAccount) { ?>
                    <tr>
                        <td><?= $bankAccount['name'] ?></td>
                        <td>
                            <a href="/pages/bank-account/update-bank-account.php?account-code=<?= $bankAccount['account_code'] ?>">
                            <?= $bankAccount['account_code'] ?>
                            </a>
                        </td>
                        <td><?= number_format($bankAccount['balance'],2,".",",") ?></td>
                        <td><?= $bankAccount['description'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="1"><strong>Total Balance</strong></td>
                        <td><strong><?= number_format($totalBankBalance ?? 0,2,".",",") ?></strong></td>
                        <td></td>
                    </tr>
            </table>

        </main>

        <aside>
            <h1>Accounts</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
