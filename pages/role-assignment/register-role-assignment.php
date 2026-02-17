<?php
namespace PineappleFinance\Pages\RoleAssignment;

require_once "../../_config.php";

require_once "../../modules/role_assignment_service.php";
use PineappleFinance\Services\RoleAssignmentService;
$roleAssignmentService = new RoleAssignmentService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "username" => $_POST['username'],
        "role" => $_POST['role'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $roleAssignmentService->GetSanitisedInput($input, true);

    if ($sanitisedInput) {
        $response = $roleAssignmentService->RegisterRoleAssignment($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$loginIdNamePairList = $roleAssignmentService->GetLoginIdNamePairList();
$roleIdNamePairList = $roleAssignmentService->GetRoleIdNamePairList();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Role Assignment | Pine</title>
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

    <?php include '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <h1>Register Role Assignment</h1>

            <form method="post">

                <div class="row">
                    <div class="four columns">
                        <label for="inputUsername">Username</label>
                        <select class="u-full-width" id="selectUsername" name="username">
                            <option value="">Select Username</option>
                            <?php foreach ($loginIdNamePairList as $idNamePair) {?>
                            <option value="<?php echo $idNamePair['id']; ?>"><?php echo $idNamePair['username']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputRoleName">Role Name</label>
                        <select class="u-full-width" id="selectRoleName" name="role">
                            <option value="">Select Role</option>
                            <?php foreach ($roleIdNamePairList as $idNamePair) {?>
                            <option value="<?php echo $idNamePair['id']; ?>"><?php echo $idNamePair['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Register Role Assignment" />
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Assign Role</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
