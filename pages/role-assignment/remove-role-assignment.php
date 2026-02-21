<?php
namespace PineappleFinance\Pages\RoleAssignment;

if ( !isset($_GET['username']) || !isset($_GET['role']) ) {
    header("Location: /pages/role-assignment/index.php");
    exit();
}
$username = htmlspecialchars($_GET['username']);
$role = htmlspecialchars($_GET['role']);

require_once "../../_config.php";

require_once "../../modules/role_assignment_service.php";
use PineappleFinance\Services\RoleAssignmentService;
$roleAssignmentService = new RoleAssignmentService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "username" => $username,
        "role" => $role,
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $roleAssignmentService->GetSanitisedInput($input, true);

    if ($sanitisedInput) {
        $response = $roleAssignmentService->RemoveRoleAssignment($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$loginIdNamePairList = $roleAssignmentService->GetLoginIdNamePairList();
$roleIdNamePairList = $roleAssignmentService->GetRoleIdNamePairList();

$roleAssignment = $roleAssignmentService->GetRoleAssignment($username, $role)[0];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Remove Role Assignment | Pine</title>
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

            <h1>Remove Role Assignment</h1>

            <form method="post">

                <div class="row">
                    <div class="four columns">
                        <label for="inputUsername">Username</label>
                        <input class="u-full-width" type="text" placeholder="Username" id="inputUsername" name="username" readonly
                            value="<?php echo isset($roleAssignment['username']) ? $roleAssignment['username'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputRoleName">Role Name</label>
                        <input class="u-full-width" type="text" placeholder="Role Name" id="inputRoleName" name="role_name" readonly
                            value="<?php echo isset($roleAssignment['role_name']) ? $roleAssignment['role_name'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Remove Role Assignment" />
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Remove Role</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
