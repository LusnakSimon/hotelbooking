<?php
/** @var \App\Models\Hotel $hotel */
/** @var array $rooms */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

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
								href="<?= $link->url('hotel.deleteRoom', ['id' => $room->getId()]) ?>"
								onclick="return confirm('Are you sure?')">Delete</a>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<div class="alert alert-danger"><?= $error ?></div>
			<?php endif; ?>
			<form method="post" action="<?= $link->url('hotel.addRoom', ['hotel_id' => $hotel->getId()]) ?>">
				<div class="input-group mb-3">
					<input name="beds" type="number" class="form-control" placeholder="Beds" required>
					<button class="btn btn-outline-secondary" type="submit">Add room</button>
				</div>
			</form>
			<a class="btn btn-secondary mb-3" href="<?= $link->url('hotel.detail', ['id' => $hotel->getId()]) ?>">Back to Hotel</a>
		</div>
	</div>
</div>

