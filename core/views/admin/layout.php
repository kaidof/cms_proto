<!doctype html>
<html lang="<?= $pageLang ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle ?? 'Admin' ?></title>

    <?php foreach (assets()->getByType('css') as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach; ?>

    <?php foreach (assets()->getByType('header_js') as $script): ?>
        <script type="text/javascript" src="<?= $script ?>" defer></script>
    <?php endforeach; ?>
</head>
<body>

    <header>
        <div>
            <a href="<?= route('admin.index') ?>">HEADER LOGO</a>
        </div>

        <?php if (auth()->isLoggedIn()): ?>
        <div class="right-side-container">
            <span class="username"><?= auth()->user()->name ?? '' ?></span>

            <form method="POST" action="<?= route('admin.logout') ?>">
                <input type="hidden" name="_token" value="">
                <input type="submit" value="Logout">
            </form>
        </div>
        <?php endif; ?>
    </header>

    <aside class="left-menu">
        <!-- left side menu content -->
        <nav>
            <ul>
                <?php if (isset($leftMenu) && count($leftMenu)): ?>
                <?php foreach ($leftMenu as $item): ?>
                    <li>
                        <a href="<?= url()->route($item['url_name']) ?>" class="<?= request()->routeIs($item['url_name']) ? 'active' : '' ?>"><?= $item['title'] ?></a>
                        <?php if (isset($item['submenu']) && count($item['submenu'])): ?>
                            <ul>
                                <?php foreach ($item['submenu'] as $subitem): ?>
                                    <li><a href="<?= url()->route($subitem['url']) ?>" class="<?= request()->routeIs($subitem['url_name']) ? 'active' : '' ?>"><?= $subitem['title'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <!-- main content area -->
        <?= $content ?? '' ?>
    </main>

<!-- javascript files -->
<script src="<?= asset('js/admin.js') ?>"></script>
<script>
    // Initialize Core object and set API URL
    var core = Object.create(Core);
    core.setApiUrl("<?= url()->getFullBaseUrl() ?>");
</script>

<?php foreach (assets()->getByType('js') as $script): ?>
<script type="text/javascript" src="<?= $script ?>" defer></script>
<?php endforeach; ?>

</body>
</html>
