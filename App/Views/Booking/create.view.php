<?php
/** @var object $room */
/** @var string|null $error */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>Book Room</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="card p-3">
                <p><strong>Hotel:</strong> <?= htmlspecialchars($hotel ? $hotel->getName() : '') ?></p>
                <p><strong>Room:</strong> #<?= htmlspecialchars($room->getId()) ?> — Beds: <?= htmlspecialchars($room->getBeds()) ?></p>
                <form method="post">
                    <div class="mb-2">
                        <label for="from" class="form-label">Check-in</label>
                        <input id="from" name="from" type="date" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="until" class="form-label">Check-out</label>
                        <input id="until" name="until" type="date" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary">Confirm booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
