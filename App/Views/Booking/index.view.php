<?php
/** @var array $bookings */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

<div class="container mt-4">
    <h3>Bookings</h3>
    <div class="list-group mt-3">
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info">No bookings found.</div>
        <?php endif; ?>
        <?php foreach ($bookings as $b): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div><strong><?= htmlspecialchars($b['hotel_name'] ?? ($b['hotel_id'] ?? 'Hotel')) ?></strong></div>
                    <div>Room: <?= htmlspecialchars($b['room_id']) ?> — Beds: <?= htmlspecialchars($b['beds'] ?? '') ?></div>
                    <?php if (isset($b['user_email'])): ?>
                        <div class="small text-muted">Guest: <?= htmlspecialchars($b['user_email']) ?></div>
                    <?php endif; ?>
                    <div class="small">From: <?= htmlspecialchars($b['from']) ?> — Until: <?= htmlspecialchars($b['until']) ?></div>
                </div>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="<?= $link->url('booking.edit') ?>&id=<?= urlencode($b['id']) ?>">Edit</a>
                    <a class="btn btn-sm btn-danger"
                       data-bs-toggle="modal" data-bs-target="#confirm-modal"
                       data-href="<?= $link->url('booking.delete') ?>&id=<?= urlencode($b['id']) ?>"
                       data-message="Delete this booking?">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
