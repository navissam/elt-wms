<?php
$empID = '';
$name = '用户名';
$role = '角色';
$last_login = '上次登入时间 - ';
if (session()->get('logged_in')) {
    $empID = session()->get('empID');
    $db = \Config\Database::connect();

    $user = $db->table('user');
    $user->join('role', 'role.roleID = user.roleID', 'inner');
    $user->select([
        'user.name as u_name',
        'user.roleID',
        'role.name as r_name'
    ]);
    $user->where([
        'empID' => $empID,
        'user.deleted_at' => null,
        'user.status' => 1,
    ]);
    $r = $user->get()->getFirstRow('array');
    $name = $r['u_name'];
    $role = $r['r_name'];

    $today = date('Y-m-d');

    $create = date_create(date('Y-m-d'));
    date_modify($create, '-1 day');
    $yesterday = date_format($create, 'Y-m-d');

    $timestamp = session()->get('lastLogin');
    $getLast = date_create($timestamp);
    $last = date_format($getLast, 'Y-m-d');

    if ($timestamp === null || $timestamp === '') {
        $last_login .= '新用户';
    } else if ($today == $last) {
        $last_login .= 'Today, ';
        $last_login .= date_format($getLast, 'h:i:s A');
    } else if ($yesterday == $last) {
        $last_login .= 'Yesterday, ';
        $last_login .= date_format($getLast, 'h:i:s A');
    } else {
        $last_login .= date_format($getLast, 'd-M-Y h:i:s A');
    }
}
?>

<nav class="main-header navbar navbar-expand navbar-dark navbar-bbb">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url() ?>/dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline"><?= $name; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-bbb">
                    <img src="<?= base_url() ?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

                    <p>
                        <?= $name . ' - ' . $role; ?>
                        <small><?= $last_login; ?></small>
                    </p>
                </li>
                <!-- Menu Body -->
                <!-- <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div> -->
                <!-- /.row -->
                <!-- </li> -->
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="<?= base_url() ?>/account/changepass" id="changepass" class="btn btn-default btn-flat">更改密码</a>
                    <a href="<?= base_url() ?>/logout" class="btn btn-default btn-flat float-right">登出</a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>