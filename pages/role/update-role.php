<?php
namespace PineappleFinance\Pages\BankAccount;

if ( !isset($_GET['name']) ) {
    header("Location: /pages/role/index.php");
    exit();
}
$role_name = htmlspecialchars($_GET['name']);

require_once "../../_config.php"; // NOSONAR: Manual auto-loader
use PineappleFinance\Includes\DefaultPageHeader;


require_once "../../modules/role_service.php";
use PineappleFinance\Services\RoleService;
$roleService = new RoleService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "name" => $role_name,
        "description" => $_POST['description'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $roleService->GetSanitisedInput($input, false);

    if ($sanitisedInput) {
        $response = $roleService->UpdateRole($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}


$role = $roleService->GetRole($role_name);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Role | Pine</title>
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

            <h1>Update Role</h1>

            <form method="post">

                <div class="row">
                    <div class="four columns">
                        <label for="inputName">Name</label>
                        <input class="u-full-width" type="text" placeholder="Name" id="inputName" name="name" 
                            value="<?php echo isset($role['name']) ? $role['name'] : ''; ?>" readonly />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Description" id="inputDescription" name="description" 
                            value="<?php echo isset($role['description']) ? $role['description'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Role">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Roles</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
