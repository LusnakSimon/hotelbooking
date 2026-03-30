<?php
/** @var \App\Models\Hotel $hotel */
/** @var \Framework\Support\View $view */
$view->setLayout('root');
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3><?= ($hotel->getId() === null) ? 'Create hotel' : 'Edit hotel' ?></h3>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-2">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" required value="<?= ($hotel->getId() === null) ? '' : htmlspecialchars($hotel->getName()) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Location</label>
                    <input name="location" class="form-control" required value="<?= ($hotel->getId() === null) ? '' : htmlspecialchars($hotel->getLocation()) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Address</label>
                    <input name="adress" class="form-control" required value="<?= ($hotel->getId() === null) ? '' : htmlspecialchars($hotel->getAdress()) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Image</label>
                    <?php if ($hotel->getId() !== null && !empty($hotel->getImagePath())): ?>
                        <div class="mb-1"><img src="<?= $link->asset($hotel->getImagePath()) ?>" style="height:80px;object-fit:cover;" alt="Current image"></div>
                    <?php endif; ?>
                    <input name="image" type="file" class="form-control" accept="image/*">
                </div>
                <div class="mb-2">
                    <label class="form-label">Price</label>
                    <input name="price" type="number" step="0.01" min="0" class="form-control" required value="<?= ($hotel->getId() === null) ? '' : htmlspecialchars($hotel->getPrice()) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required><?= ($hotel->getId() === null) ? '' : htmlspecialchars($hotel->getDescription()) ?></textarea>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
