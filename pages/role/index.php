<?php
namespace PineappleFinance\Pages\Role;

require_once "../../_config.php"; // NOSONAR: Manual auto-loader
use PineappleFinance\Includes\DefaultPageHeader;


require_once "../../modules/role_service.php";
use PineappleFinance\Services\RoleService;
$roleService = new RoleService();
    
session_start();
$session_user_id = require_authenticated_user();

$roleList = $roleService->GetRoleList();

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

    <?php DefaultPageHeader::render(); ?>

    <article class="with-aside">

        <main>
            
            <nav>
                <a href="/pages/role/register-role.php" class="button button-primary">Register role</a>
            </nav>

            <table class="u-full-width accounts">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Update At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roleList as $role) { ?>
                    <tr>
                        <td>
                            <a aria-label="Update role <?= $role['name'] ?>" href="/pages/role/update-role.php?name=<?= $role['name'] ?>">
                            <?= $role['name'] ?>
                            </a>
                        </td>
                        <td><?= $role['description'] ?></td>
                        <td><?= $role['update_at']->format('Y-m-d H:i:s') ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </main>

        <aside>
            <h1>Roles</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
