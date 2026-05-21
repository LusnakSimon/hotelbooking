<?php

/** @var string $contentHTML */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= App\Configuration::APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= $link->asset('css/styl.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="<?= $link->url('home.index') ?>"><?= App\Configuration::APP_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('hotel.index') ?>">Hotels</a>
                </li>
                <?php if ($user->isLoggedIn()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('booking.index') ?>">Bookings</a>
                    </li>
                    <?php if ($user->getRole() === 'manager') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $link->url('admin.index') ?>">Manage Hotels</a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-sm-center">
                <?php if ($user->isLoggedIn()) { ?>
                    <li class="nav-item">
                        <span class="navbar-text me-2">Logged in: <b><?= htmlspecialchars($user->getName()) ?></b></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('auth.logout') ?>">Log out</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= App\Configuration::LOGIN_URL ?>">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('auth.register') ?>">Register</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>
</body>
</html>
