<?php ob_start(); ?>
<div class="project-boxes jsListView dontresize">
    <table>
        <thead>
            <tr>
                <th class="avatar"></th>
                <th>User ID</th>
                <th>User Name</th>
                <th>User Gems</th>
                <th>User Status</th>
                <th>Admin Actions</th>
            </tr>
        </thead>
        <tbody id="user_list">
        </tbody>
    </table>
</div>

<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'Users Panel',
    'tab' => 'user',
    'content' => $html,
    'scripts' => [
        'js/admin/userList.js'
    ]
]) ?>