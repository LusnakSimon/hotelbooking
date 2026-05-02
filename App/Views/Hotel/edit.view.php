<?php
/** Edit page for hotel: includes form and rooms management */
/** @var \App\Models\Hotel $hotel */
/** @var array $rooms */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

<?php include __DIR__ . '/form.view.php'; ?>

<?php if ($hotel->getId() !== null): ?>
	<div class="container mt-4">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<h4>Rooms</h4>
				<?php if (empty($rooms)): ?>
					<div class="alert alert-info">No rooms yet.</div>
				<?php else: ?>
					<ul class="list-group mb-3">
						<?php foreach ($rooms as $room): ?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span>Room #<?= $room->getId() ?> — Beds: <?= $room->getBeds() ?></span>
								<span>
									<a class="btn btn-sm btn-danger"
								   data-bs-toggle="modal" data-bs-target="#confirm-modal"
								   data-href="<?= $link->url('hotel.deleteRoom') ?>&id=<?= urlencode($room->getId()) ?>"
								   data-message="Delete Room #<?= $room->getId() ?> (<?= $room->getBeds() ?> beds)?">Delete</a>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<form method="post" action="<?= $link->url('hotel.addRoom') ?>">
					<input type="hidden" name="hotel_id" value="<?= $hotel->getId() ?>">
					<div class="input-group mb-3">
						<input name="beds" type="number" class="form-control" placeholder="Beds" required>
						<button class="btn btn-outline-secondary" type="submit">Add room</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>
