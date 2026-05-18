<?php

/** @var string|null $message */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('auth');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Login</h5>
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-danger"><?= $message ?></div>
                    <?php endif; ?>
                    <form class="form-signin" method="post" action="<?= $link->url('auth.login') ?>">
                        <div class="form-label-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" type="email" id="email" class="form-control" placeholder="email@example.com"
                                   required autofocus>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" id="password" class="form-control"
                                   placeholder="Password" required>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Log in
                            </button>
                        </div>
                    </form>
                    <div class="mt-3 d-flex justify-content-center gap-2">
                        <a class="btn btn-outline-secondary" href="<?= $link->url('auth.register') ?>">Create an account</a>
                        <a class="btn btn-outline-secondary" href="<?= $link->url('home.index') ?>">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
