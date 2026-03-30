<?php

/** @var object $hotel */
/** @var array $rooms */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <?php if (!empty($hotel->getImagePath())): ?>
                    <img src="<?= $link->asset($hotel->getImagePath()) ?>" class="card-img-top" alt="<?= htmlspecialchars($hotel->getName()) ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($hotel->getName()) ?></h3>
                    <p class="text-muted"><?= htmlspecialchars($hotel->getLocation()) ?></p>
                    <p><?= nl2br(htmlspecialchars($hotel->getDescription())) ?></p>
                </div>
            </div>
            <h4 class="mt-4">Rooms</h4>
            <div class="list-group">
                <?php foreach ($rooms as $room): ?>
                    <?php $r = $room; ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Room #<?= htmlspecialchars($r->getId()) ?></strong>
                            <div class="text-muted small"><?= htmlspecialchars('Beds: ' . $r->getBeds()) ?></div>
                        </div>
                        <div class="text-end">
                            <div><?= htmlspecialchars(number_format($hotel->getPrice(), 2)) ?> € / night</div>
                            <?php if (!(isset($user) && $user->isLoggedIn() && $user->getRole() === 'manager')): ?>
                                <a class="btn btn-sm btn-primary mt-2" href="<?= $link->url('booking.create') ?>&room_id=<?= urlencode($r->getId()) ?>">Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-4">
                <div class="card p-3">
                <h5>Details</h5>
                <p><strong>Price:</strong> <?= htmlspecialchars(number_format($hotel->getPrice(), 2)) ?> € / night</p>
            </div>
        </div>
    </div>
</div>
