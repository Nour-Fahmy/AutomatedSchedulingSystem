# AlignUp

**AlignUp** is a Laravel web application for academic scheduling. It brings student appointments, faculty office hours, role-based dashboards, and a discussion forum into one interface. The landing page describes use cases such as advising and tutoring.

## Features

- **Appointments:** Students can view, create, and cancel appointments.
- **Faculty availability:** Faculty users can add and remove office-hour rules from the dashboard.
- **Role-aware dashboard:** Student, faculty, and admin views are selected by user role.
- **Administration:** Routes support user creation, role changes, deletion, settings updates, and service toggles.
- **Forum:** Users can create discussions and post replies.
- **Accounts:** Registration, login, logout, and password reset routes.
- **Google Calendar:** OAuth connection, callback, and disconnection routes are present; using the integration requires local configuration.

## Built with

- PHP 8.2+ and Laravel 12
- Blade views, Tailwind CSS, and Vite
- SQLite as the default local database
- Google API Client

## Run locally

Requirements: PHP 8.2+, Composer, Node.js/npm, and SQLite.

```bash
git clone https://github.com/Nour-Fahmy/AutomatedSchedulingSystem.git
cd AutomatedSchedulingSystem
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Open the URL printed by `php artisan serve`. To work on the front end, run `npm run dev` in another terminal. The repository also defines `composer setup`, `composer dev`, and `composer test` scripts.

The example environment file uses SQLite. Configure external services such as Google Calendar locally before using them. Keep real credentials in `.env`, which should stay outside version control.

## Project structure

- `app/` — controllers, models, and application logic
- `routes/web.php` and `routes/auth.php` — application and authentication routes
- `resources/views/` — Blade pages
- `database/` — migrations and local data setup
- `tests/` — automated tests

## Scope

This repository contains the application source and development history. A public deployment or production calendar configuration is not documented here.