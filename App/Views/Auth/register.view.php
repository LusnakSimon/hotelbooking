<?php

/** @var array|null $data */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('auth');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Register</h5>
                    <div id="reg-error" class="alert alert-danger d-none"></div>
                    <form id="reg-form" method="post" action="<?= $link->url('auth.register') ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" type="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm password</label>
                            <input name="confirm_password" type="password" id="confirm_password" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Register</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="<?= $link->url('auth.login') ?>">Back to login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
