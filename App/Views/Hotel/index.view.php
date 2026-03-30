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
            <form id="filter-form" data-filter-url="<?= htmlspecialchars($link->url('hotel.filter')) ?>" data-detail-url="<?= htmlspecialchars($link->url('hotel.detail')) ?>">
                <div class="mb-2">
                    <label for="location" class="form-label">Location</label>
                    <select id="location" name="location" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($locations as $loc): ?>
                            <?php $val = is_array($loc) ? $loc['location'] : $loc->location; ?>
                            <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($val) ?></option>
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
            <div id="hotel-list" data-asset-base="<?= htmlspecialchars($link->asset('')) ?>" class="row">
                <?php foreach ($hotels as $hotel): ?>
                    <?php $h = $hotel; ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($h->getImagePath())): ?>
                                <img src="<?= $link->asset($h->getImagePath()) ?>" class="card-img-top" alt="<?= htmlspecialchars($h->getName()) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($h->getName()) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($h->getLocation()) ?> — <?= htmlspecialchars(number_format($h->getPrice(), 2)) ?> €</p>
                                <a class="btn btn-sm btn-outline-primary" href="<?= $link->url('hotel.detail', ['id' => $h->getId()]) ?>">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= $link->asset('js/hotel-index.js') ?>"></script>
