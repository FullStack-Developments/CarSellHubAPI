# CarSellHubAPI

Car Sell Hub API is a Laravel-based RESTful API designed to manage car listings, advertisements, and user reviews with robust role-based access control.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Authentication & Authorization](#authentication--authorization)
- [Exception Handling](#exception-handling)
- [Contributing](#contributing)
- [License](#license)

## Overview

The CarSellHubAPI provides a backend service enabling users to:

- Browse and manage car listings
- Create and manage advertisements
- Leave and moderate reviews
- Configure site settings

The system supports three primary user roles:

- Admin: Full access to all resources and settings
- Seller: Can create and manage their own car listings and advertisements
- Client: Can browse listings and leave reviews

## Features

### Authentication & User Management

- User registration with role assignment
- Login and logout functionality
- Email verification using OTP tokens
- Password reset capabilities
- Role-based access control

### Car Management

- Create car listings with multiple images
- View cars with filtering options (brand, model, price, etc.)
- Update car information
- Delete car listings (admin only)
- Manage car status (sold/unsold)

### Advertisement Management

- Create promotional content with images and links
- Track views and clicks (hits)
- Date-based activation (start_date and end_date)
- Status workflow (pending → approved/rejected)
- Access controls based on user roles

### Review System

- Create reviews for specific cars
- Moderation workflow for review approval
- Public/private review visibility
- Car-specific review browsing
- Seller-specific views to see reviews on their cars

### Settings Management

- Manage website name, logo, and branding
- Configure contact information
- Set social media links
- Language preferences

## System Architecture

The CarSellHubAPI follows a layered architecture pattern to maintain separation of concerns and improve maintainability:

- Controllers: Handle HTTP requests and responses
- Services: Contain business logic for features like car management, advertisements, and reviews
- Models: Represent the application's data structures
- Repositories: Abstract data access logic
- Middleware: Handle request filtering and authentication

## Installation

1. Clone the repository:

   
    `bash
    git clone https://github.com/yourusername/CarSellHubAPI.git
    cd CarSellHubAPI

2. Install dependencies:


    composer install
    npm install

3. Environment setup:


    cp .env.example .env
    php artisan key:generate

4. Database setup:


    Configure your database settings in the .env file.
    Run migrations and seeders:
    php artisan migrate --seed

5. Run the application:


    php artisan serve

API Endpoints

For a detailed list of API routes and their functionalities, please refer to the API Routes section in the documentation.

Authentication & Authorization

Authentication: Implemented using Laravel Sanctum for API token authentication.

Authorization: Role-based access control ensures that users can only access resources permitted by their roles.


Exception Handling

The application provides robust exception handling through a centralized handler that formats all API responses consistently.

Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

License

This project is open-source and available under the MIT License.
