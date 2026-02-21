<?php

require_once "_config.php";
use PineappleFinance\Includes\DefaultPageHeader;

session_start();

if (!isset( $_SESSION['username'] )) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_destroy();
    header("Location: /login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Pineapple Finance Site" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  <link rel="stylesheet" href="css/site.css" />
</head>
<body>

    <?php DefaultPageHeader::render(); ?>

    <article class="with-aside">

        <main>

        <p>Are you sure you want to log out?</p>

        <form method="POST">
            <div class="row">
                <div class="six columns">
                    <input class="button-primary" type="submit" value="Log out">
                </div>
            </div>
        </form>

        </main>

        <aside>
            <h1>Welcome</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>

