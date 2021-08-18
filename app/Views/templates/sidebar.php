<?php
$db      = \Config\Database::connect();

$roleID = session()->get('roleID') ?? 0;
if ($roleID != 0) {
    $rp = $db->table('role_permission');
    $rp->select('role_permission.permID');
    $rp->join('permission', 'permission.permID = role_permission.permID', 'inner');
    $rp->where('permission.deleted_at', null);
    $rp->where('permission.status', 1);
    $rp->where('roleID', $roleID);
    $rp_ = $rp->get()->getResultArray();
    $permIDs = [];
    foreach ($rp_ as $value) {
        array_push($permIDs, intval($value['permID']));
    }
    // dd($permIDs);
    // $permIDs = [4, 0];
    $b1 = $db->table(['menu as a', 'menu as b']);
    $b1->select('a.*');
    $b1->distinct();
    $b1->where('a.code = b.parentCode');
    $b1->where('a.status', 1);
    $b1->where(['a.deleted_at' => null]);
    $b1->whereIn('b.permID', $permIDs);
    $b1->orderBy('a.ord', 'ASC');
    $query1 = $b1->getCompiledSelect();

    $b2 = $db->table('menu');
    $b2->where('status', 1);
    $b2->where(['deleted_at' => null]);
    $b2->whereIn('permID', $permIDs);
    $b2->orderBy('ord', 'ASC');
    $query2 = $b2->getCompiledSelect();

    $builder = $db->query('SELECT * FROM ((' . $query1 . ') UNION (' . $query2 . ')) AS uni ORDER BY uni.ord, uni.menuID ASC');
    // dd($query2);
    // $builder = $db->query($query1);
    // dd($builder);
    $result = $builder->getResultArray();
    // dd($result);
} else {
    $result = [];
}
?>
<aside class="main-sidebar sidebar-dark-bbb elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url(); ?>" class="brand-link">
        <img src="<?= base_url(); ?>/dist/img/logo.png" alt="Cangku" class="brand-image img-circle elevation-3 bg-white" style="opacity: 1">
        <span class="brand-text font-weight-light text-white">仓储管理系统</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <?php foreach ($result as $r) : ?>
                    <?php if ($r['parentCode'] === '' || $r['parentCode'] === null) : ?>
                        <li class="nav-item <?= isset($active[$r['code']]) ? 'menu-open' : '' ?>">
                            <a href="<?= $r['type'] === 1 ? '#' : $r['url']; ?>" class="nav-link <?= isset($active[$r['code']]) ? 'active' : '' ?>">
                                <i class="nav-icon <?= $r['icon']; ?>"></i>
                                <p>
                                    <?= $r['name']; ?>
                                    <?php if ($r['type'] == 1) : ?>
                                        <i class="right fas fa-angle-left"></i>
                                    <?php endif; ?>
                                </p>
                            </a>
                            <?php if ($r['type'] == 1) :                            ?>
                                <ul class="nav nav-treeview">
                                    <?php foreach ($result as $r1) : ?>
                                        <?php if ($r1['parentCode'] == $r['code']) : ?>
                                            <li class="nav-item">
                                                <a href="<?= $r1['type'] === 1 ? '#' : $r1['url']; ?>" class="nav-link <?= isset($active[$r['code']][$r1['code']]) ? 'active' : '' ?>">
                                                    <i class="<?= $r1['icon']; ?> nav-icon"></i>
                                                    <p><?= $r1['name']; ?></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif;                            ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>