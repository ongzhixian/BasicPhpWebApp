<?php
namespace PineappleFinance\Pages\Bank;

session_start();

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();
$banks = $bankService->GetBankList();

// session_start();
// require_once "../modules/bank_module.php";
// $bankModule = new Bank();
// $banks = $bankModule->GetList();
// print_r($banks);
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
                <a href="/pages/bank/register-bank.php" class="button button-primary">Register bank</a>
                <!-- <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <table class="u-full-width">
                <thead>
                    <tr>
                        <th>Bank Code</th>
                        <th>Bank Full Name</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banks as $bank) { ?>
                        <tr>
                            <td>
                                <a href="/pages/bank/update-bank.php?code=<?php echo $bank['name']; ?>">
                                <?php echo $bank['name']; ?>
                                </a>
                            </td>
                            <td><?= $bank['full_name'] ?></td>
                            <td><?php echo $bank['create_at']->format('Y-m-d H:i:s'); ?></td>
                            <td><?= $bank['update_at']->format('Y-m-d H:i:s') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </main>

        <aside>
            <h1>Banks</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
