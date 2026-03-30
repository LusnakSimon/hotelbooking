<?php
/** @var \App\Models\Booking $booking */
/** @var string|null $error */
$view->setLayout('root');
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>Edit Booking</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="card p-3">
                <form method="post">
                    <div class="mb-2">
                        <label for="from" class="form-label">Check-in</label>
                        <input id="from" name="from" type="date" class="form-control" value="<?= htmlspecialchars($booking->getFrom()) ?>">
                    </div>
                    <div class="mb-2">
                        <label for="until" class="form-label">Check-out</label>
                        <input id="until" name="until" type="date" class="form-control" value="<?= htmlspecialchars($booking->getUntil()) ?>">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
