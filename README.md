# Web Builder – Admin CMS System

## Overview

The Web Builder project is a custom content management system (CMS) designed to allow administrators to efficiently manage website content through a secure backend interface. The system provides capabilities such as page creation, content editing, media management, and navigation structure control.

## Features

- **Admin Authentication**  
  Secure login system for administrators, including password reset functionality.

- **Page Management**  
  Create, edit, and delete complete web pages, including:
  - Updating page titles
  - Managing text blocks
  - Uploading and organizing images

- **Navigation Management**  
  Control the navigation bar by:
  - Reordering navigation items
  - Automatically adding new pages to the menu

- **Account Management**  
  Administrators can update their email address with a verification flow to ensure accuracy.

## Technologies Used

- Laravel (PHP Framework)
- MySQL or compatible database
- Blade templating engine
- Bootstrap or custom CSS framework
- JavaScript (for interactivity)
- RESTful API (if applicable)

## Getting Started

To run the project locally:

1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Copy `.env.example` to `.env` and configure your database settings.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate` to create the database tables.
6. Start the development server with `php artisan serve`.

## Folder Structure

├── app/
├─┬ resources/
│ ├── views/
│ └── js/
├─┬ routes/
│ └── web.php
├── public/
├── database/
└── migrations/

## License

This project is licensed under the MIT License.
Copyright (c) 2025 Steven Heijn
