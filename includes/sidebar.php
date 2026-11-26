<ul class="navbar-nav sidebar sidebar-dark">
    <a class="sidebar-brand" href="<?= BASE_URL . home_path() ?>">Main Store 2.0</a>
    <hr class="sidebar-divider">

    <!-- menus que tiene el perfil -->
    <?php foreach (user_menus() as $menu): ?>
        <li class="nav-item <?= $menu['code'] == $active_menu ? 'active' : '' ?>">
            <a class="nav-link" href="<?= BASE_URL . e($menu['url']) ?>">
                <?php if ($menu['icon']): ?>
                    <i class="<?= e($menu['icon']) ?>"></i>
                <?php endif; ?>
                <span><?= e($menu['name']) ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
