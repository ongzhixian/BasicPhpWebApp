<?php

require_once "php-scripts/authentication_module.php";
// require_once "session.php";

$feedbackMessage = "";
$login = new LoginModule();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginIsAdded = $login->AddLogin($username, $password);
    if ($loginIsAdded) {
        $feedbackMessage = "Login added successfully.";
    } else {
        $feedbackMessage = "Failed to add login.";
    }
}

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
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
  <link rel="stylesheet" href="css/site.css" />
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <article>

        <form method="post">
            <div class="row">
                <div class="four columns">
                    <label for="inputUsername">Username</label>
                    <input class="u-full-width" type="text" placeholder="Account username" id="inputUsername" name="username">
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    <label for="inputPassword">Password</label>
                    <input class="u-full-width" type="password" placeholder="Account password" id="inputPassword" name="password">
                </div>
            </div>

            <div class="row">
                <input class="button-primary" type="submit" value="Log in">
                <span class="error-message"><?php echo htmlspecialchars($feedbackMessage); ?></span>
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

    </article>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container" style="display: flex; flex-direction: column; justify-content: center;">

<!--
    <div class="row" style="padding: 1em; border: 0px solid #11f1f1;">
      <div>
        <h1 margin-bottom: 1em;">Site 9034 - a PHP Site</h1>

        <p>
            <?php
            echo "Hi, I'm a PHP script!";
            ?>
        </p>

      </div>
    </div>
-->

  </div>

</body>
</html>
