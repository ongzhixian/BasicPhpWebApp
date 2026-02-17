<?php
namespace PineappleFinance\Pages\FixedDeposit;


require_once "../../_config.php";

require_once "../../modules/fixed_deposit_service.php";
use PineappleFinance\Services\FixedDepositService;
$fixedDepositService = new FixedDepositService();

session_start();
$session_user_id = require_authenticated_user();

$fixedDeposits = $fixedDepositService->GetFixedDepositList($session_user_id);
$totalPlacementAmount = $fixedDepositService->GetTotalFixedDepositPlacementAmount($session_user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fixed Deposits | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Fixed deposit list" />
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

            <nav>
                <a href="/pages/fixed-deposit/register-fixed-deposit.php" class="button button-primary">Register fixed deposit</a>
                <!-- <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <table class="u-full-width">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Description</th>
                        <th>Maturity Date</th>
                        <th>Interest Rate</th>
                        <th>Placement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fixedDeposits as $fixedDeposit) { ?>
                        <tr>
                            <td><?= $fixedDeposit['name'] ?></td>
                            <td>
                                <a href="/pages/fixed-deposit/update-fixed-deposit.php?id=<?= $fixedDeposit['id'] ?>">
                                <?= $fixedDeposit['description'] ?>
                                </a>
                            </td>
                            <td><?= $fixedDeposit['maturity_date']->format('Y-m-d') ?></td>
                            <td><?= $fixedDeposit['interest_per_annum_percentage'] ?>%</td>
                            <td><?= number_format($fixedDeposit['placement_amount'], 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                        <td><?= number_format($totalPlacementAmount[0]['totalPlacementAmount'] ?? 0, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

        </main>

        <aside>
            <h1>Fixed Deposits</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
