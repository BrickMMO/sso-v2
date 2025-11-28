<?php

security_check();
admin_check();

if (isset($_GET['delete'])) 
{
    $query = 'DELETE FROM users 
        WHERE id = '.$_GET['delete'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Delete Success', 'User has been deleted.');
    header_redirect('/admin/dashboard');
}

define('APP_NAME', 'My Account');
define('PAGE_TITLE', 'Admin Dashboard');
define('PAGE_SELECTED_SECTION', 'users');
define('PAGE_SELECTED_SUB_PAGE', '/admin/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT * 
    FROM users
    ORDER BY created_at DESC';    
$result = mysqli_query($connect, $query);

$users_count = mysqli_num_rows($result);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/sso.png"
        height="50"
        style="vertical-align: top"
    />
    Single Sign On
</h1>

<p>
    Number of users: <span class="w3-tag w3-blue"><?=$users_count?></span>    
</p>

<hr />

<h2>User List</h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th class="bm-table-icon"></th>
        <th>Name</th>
        <th>Email</th>
        <th>GitHub</th>
        <th>URL</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while ($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <?php if($record['avatar']): ?>
                    <img src="<?=$record['avatar']?>" width="70">
                <?php endif; ?>
            </td>
            <td>
                <?=$record['first']?> <?=$record['last']?>
            </td>
            <td>
                <?=$record['email']?>
            </td>
            <td>
                <?php if($record['github_username']): ?>
                    <a href="https://github.com/<?=$record['github_username']?>"><?=$record['github_username']?></a>
                <?php endif; ?>
            </td>
            <td>
                <?php if($record['url']): ?>
                    <a href="/me/<?=$record['url']?>"><?=$record['url']?></a>
                <?php endif; ?>
            </td>
            <td>
                <a href="/admin/edit/<?=$record['id'] ?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
            <td>
                <a href="#" onclick="return confirmModal('Are you sure you want to delete the user <?=$record['first']?> <?=$record['last']?>?', '/admin/dashboard/delete/<?=$record['id'] ?>');">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a
    href="/admin/add"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-plus fa-padding-right"></i> Add User
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');

