<?php
namespace PineappleFinance\Pages\Investment;

require_once "../../_config.php";

require_once "../../modules/investment_service.php";
use PineappleFinance\Services\InvestmentService;
$investmentService = new InvestmentService();

require_once "../../modules/equity_service.php";
use PineappleFinance\Services\EquityService;
$equityService = new EquityService();

session_start();
$session_user_id = require_authenticated_user();

$investments = $investmentService->GetInvestmentList($session_user_id);
$equities = $equityService->GetEquityList($session_user_id);
// $totalPlacementAmount = $investmentService->GetTotalFixedDepositPlacementAmount();

$totalInvestmentAmount = $investmentService->GetTotalInvestmentAmount($session_user_id)[0]['totalInvestmentAmount'];
$totalEquityInvestmentAmount = $equityService->GetTotalEquityInvestmentAmount($session_user_id)[0]['totalEquityInvestmentAmount'];

$totalInvestmentAmount = $totalInvestmentAmount + $totalEquityInvestmentAmount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pine</title>
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

    <?php include_once '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <nav>
                <a href="/pages/investment/register-investment.php" class="button button-primary">Register investment</a>
                <a href="/pages/investment/register-equity.php" class="button button-primary">Register equity</a>
            </nav>

            <table class="u-full-width">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Reference Code</th>
                        <th>Investment Amount</th>
                        <th>Last Updated</th>
                        <th>Current Value</th>
                    </tr>
                </thead>
                <tbody>

<!-- 
      ,[description]
      ,[reference_code]
      ,[investment_amount]
      ,[effective_date]
      ,[current_value]
-->
                    <?php foreach ($investments as $investment) { ?>
                        <tr>
                            <td><?= $investment['type'] ?></td>
                            <td>
                                <a aria-label="Update investment <?= $investment['description'] ?>" href="/pages/investment/update-investment.php?id=<?= $investment['id'] ?>">
                                <?= $investment['description'] ?>
                                </a>
                            </td>
                            <td><?= $investment['reference_code'] ?></td>
                            <td><?= number_format($investment['investment_amount'], 2) ?></td>
                            <td><?= $investment['update_at']->format('Y-m-d') ?></td>  
                            <td><?= number_format($investment['current_value'], 2) ?></td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($equities as $equity) { ?>
                        <tr>
                            <td><?= $equity['type'] ?></td>
                            <td>
                                <a aria-label="Update equity <?= $equity['description'] ?>" href="/pages/investment/update-equity.php?id=<?= $equity['id'] ?>">
                                <?= $equity['description'] ?>
                                </a>
                            </td>
                            <td><?= $equity['reference_code'] ?></td>
                            <td><?= number_format($equity['investment_value'], 2) ?></td>
                            <td><?= $equity['update_at']->format('Y-m-d') ?></td>  
                            <td><?= number_format($equity['current_value'], 2) ?></td>  
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total</td>
                        <td><?= number_format($totalInvestmentAmount, 2) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

        </main>

        <aside>
            <h1>Investments</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
