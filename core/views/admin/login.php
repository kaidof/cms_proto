
<?php view_set_layout('admin/layout'); ?>

<div>


    <h1>Admin login</h1>

    <!-- login form  -->
    <form action="<?= route('admin.login.post') ?>" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Login">
    </form>

</div>
