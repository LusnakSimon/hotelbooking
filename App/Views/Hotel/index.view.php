<?php

/** @var array $hotels */
/** @var array $locations */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-3">
            <h5>Filter</h5>
            <form id="filter-form" data-filter-url="<?= $link->url('hotel.filter') ?>" data-detail-url="<?= $link->url('hotel.detail') ?>">
                <div class="mb-2">
                    <label for="location" class="form-label">Location</label>
                    <select id="location" name="location" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= htmlspecialchars($loc['location']) ?>"><?= htmlspecialchars($loc['location']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="min_price" class="form-label">Min price</label>
                    <input id="min_price" name="min_price" type="number" step="0.01" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="max_price" class="form-label">Max price</label>
                    <input id="max_price" name="max_price" type="number" step="0.01" class="form-control">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="col-md-9">
            <div id="hotel-list" data-asset-base="<?= $link->asset('') ?>" class="row">
                <?php foreach ($hotels as $hotel): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($hotel->getImagePath())): ?>
                                <img src="<?= $link->asset($hotel->getImagePath()) ?>" class="card-img-top" alt="<?= htmlspecialchars($hotel->getName()) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($hotel->getName()) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($hotel->getLocation()) ?> — <?= number_format($hotel->getPrice(), 2) ?> €</p>
                                <a class="btn btn-sm btn-outline-primary" href="<?= $link->url('hotel.detail', ['id' => $hotel->getId()]) ?>">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= $link->asset('js/hotel-index.js') ?>"></script>
