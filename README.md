# Hotel Booking System

A PHP MVC web application for browsing hotels, managing rooms, and booking stays. Built on the **vaiicko** framework (custom academic PHP MVC framework).

## Features

- **Hotel browsing** — list with live AJAX filtering by location and price range
- **Hotel detail** — description, image, room list with per-room booking buttons
- **Bookings** — guests create/edit/cancel bookings; managers see all bookings for their hotels
- **Manager dashboard** — create/edit/delete hotels and rooms, upload hotel images
- **Authentication** — registration (with AJAX + server-side fallback), login, logout
- **Role-based access** — two roles: `guest` (can book) and `manager` (can manage hotels)
- **Image uploads** — hotel images stored in `public/uploads/`, accepted formats: jpg, jpeg, png, gif, webp

## Roles

| Role | Can do |
|------|--------|
| Guest | Browse hotels, create/edit/delete own bookings |
| Manager | All of the above + create/edit/delete own hotels and their rooms; view all bookings for own hotels |

## Getting Started

### Requirements
- Docker + Docker Compose

### Running the app

```bash
cd docker
docker compose up -d
```

The app is available at [http://localhost](http://localhost).
Adminer (DB GUI) is at [http://localhost:8080](http://localhost:8080).

The database is initialised automatically from `docker/sql/hotelbooking.sql` on first start. Seed data (two users, two hotels, three rooms) is loaded from `docker/sql/hotelbooking_seed.sql`.

### Seed credentials

| Email | Password | Role |
|-------|----------|------|
| manager@hotel.com | Manager1! | manager |
| guest@hotel.com | Guest1! | guest |

### Resetting the database

```bash
docker compose down -v
docker compose up -d
```
