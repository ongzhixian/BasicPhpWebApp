<?php
namespace PineappleFinance\Pages\BankAccount;

require_once "../../_config.php";

require_once "../../modules/login_service.php";
use PineappleFinance\Services\LoginService;
$loginService = new LoginService();

session_start();
$session_user_id = require_authenticated_user();

$loginList = $loginService->GetLoginList();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pine</title>
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

    <?php include_once '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>
            
            <nav>
                <a href="/pages/login/register-login.php" class="button button-primary">Register login</a>
            </nav>

            <table class="u-full-width accounts">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Update At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loginList as $login) { ?>
                    <tr>
                        <td>
                            <a aria-label="Update login <?= $login['username'] ?>" href="/pages/login/update-login.php?username=<?= $login['username'] ?>">
                            <?= $login['username'] ?>
                            </a>
                        </td>
                        <td><?= $login['update_at']->format('Y-m-d H:i:s') ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </main>

        <aside>
            <h1>Logins</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
