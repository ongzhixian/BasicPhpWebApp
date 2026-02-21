<?php
namespace PineappleFinance\Pages\RoleAssignment;

require_once "../../_config.php";

require_once "../../modules/role_assignment_service.php";
use PineappleFinance\Services\RoleAssignmentService;
$roleAssignmentService = new RoleAssignmentService();
    
session_start();
$session_user_id = require_authenticated_user();

$roleAssignmentList = $roleAssignmentService->GetRoleAssignmentList();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Role list" />
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
                <a href="/pages/role-assignment/register-role-assignment.php" class="button button-primary">Register role assignment</a>
            </nav>

            <table class="u-full-width">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        
                        <th>Update At</th>
                        <th>Remove Assignment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roleAssignmentList as $role) { ?>
                    <tr>
                        <td><?= $role['username'] ?></td>
                        <td><?= $role['role_name'] ?></td>
                        <td><?= $role['update_at']->format('Y-m-d H:i:s') ?></td>
                        <td><a href="/pages/role-assignment/remove-role-assignment.php?username=<?= $role['login_id'] ?>&role=<?= $role['role_id'] ?>" class="button button-danger">Remove</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </main>

        <aside>
            <h1>Assign Roles</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
