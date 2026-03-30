<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="hero-section text-center py-5 mb-4">
    <h1 class="hero-title">Find Your Perfect Stay</h1>
    <p class="hero-subtitle text-muted">Browse hotels, compare rooms and book instantly.</p>
    <a href="<?= $link->url('hotel.index') ?>" class="btn btn-primary btn-lg mt-2">Browse Hotels</a>
</div>

<div class="container">
    <div class="row text-center g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="fs-1 mb-2">🏨</div>
                <h5 class="fw-semibold">Wide Selection</h5>
                <p class="text-muted small">Choose from hotels across multiple cities, each with detailed descriptions and room options.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="fs-1 mb-2">🔍</div>
                <h5 class="fw-semibold">Easy Filtering</h5>
                <p class="text-muted small">Filter hotels by location and price range to find exactly what you need, instantly.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="fs-1 mb-2">📅</div>
                <h5 class="fw-semibold">Simple Booking</h5>
                <p class="text-muted small">Pick your dates and confirm your booking in seconds. Manage everything from your bookings page.</p>
            </div>
        </div>
    </div>
</div>

<footer class="text-center text-muted small py-3 border-top">
    <a href="mailto:lusnak@stud.uniza.sk">Šimon Lušňák</a> &nbsp;·&nbsp;
    &copy; 2020-<?= date('Y') ?> University of Žilina, Faculty of Management Science and Informatics,
    Department of Software Technologies
</footer>
