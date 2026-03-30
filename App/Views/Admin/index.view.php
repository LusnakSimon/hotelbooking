<?php
/** @var array $hotels */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Hotels</h3>
        <a class="btn btn-primary" href="<?= $link->url('hotel.create') ?>">Create New Hotel</a>
    </div>
    <?php if (empty($hotels)): ?>
        <div class="alert alert-info">No hotels yet.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $h): ?>
                    <?php $hotel = $h; ?>
                    <tr>
                        <td><?= htmlspecialchars($hotel->getId()) ?></td>
                        <td><?= htmlspecialchars($hotel->getName()) ?></td>
                        <td><?= htmlspecialchars($hotel->getLocation()) ?></td>
                        <td><?= htmlspecialchars(number_format($hotel->getPrice(), 2)) ?> €</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= $link->url('hotel.edit') ?>&id=<?= urlencode($hotel->getId()) ?>">Edit</a>
                            <a class="btn btn-sm btn-danger"
                               data-bs-toggle="modal" data-bs-target="#confirm-modal"
                               data-href="<?= $link->url('hotel.delete') ?>&id=<?= urlencode($hotel->getId()) ?>"
                               data-message="Delete hotel &quot;<?= htmlspecialchars($hotel->getName()) ?>&quot;?">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>