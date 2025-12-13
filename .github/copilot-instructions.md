# Copilot Instructions for Laravel Project

## Overview
This project is built using the Laravel framework, which provides a robust structure for web applications. Understanding the architecture and conventions of this codebase is essential for effective collaboration and development.

## Architecture
- **MVC Pattern**: The project follows the Model-View-Controller (MVC) architecture, separating business logic, user interface, and data management.
- **Service Providers**: Key services are registered in the `app/Providers` directory, such as `AppServiceProvider.php`, which is crucial for bootstrapping application services.
- **Routing**: Routes are defined in the `routes` directory, with `web.php` handling web routes and `api.php` for API routes. Middleware is applied to routes for authentication and other functionalities.

## Developer Workflows
- **Building**: Use `npm run dev` to compile assets using Vite. Ensure that the `vite.config.js` is correctly set up to handle Vue components.
- **Testing**: Run tests using `php artisan test`. Ensure PHPUnit is configured correctly in `phpunit.xml`.
- **Debugging**: Utilize Laravel's built-in debugging tools and logging capabilities. Check logs in the `storage/logs` directory for any issues.

## Project Conventions
- **Naming Conventions**: Follow Laravel's naming conventions for models, controllers, and migrations. For example, model names should be singular (e.g., `User`), while migration files should be prefixed with timestamps.
- **Component Structure**: Vue components are organized in the `resources/js/Components` directory. Each component should encapsulate its functionality and be reusable across the application.

## Integration Points
- **Inertia.js**: This project uses Inertia.js for building single-page applications. Familiarize yourself with how Inertia integrates with Laravel and Vue.
- **Database Migrations**: Migrations are located in the `database/migrations` directory. Use `php artisan migrate` to apply migrations and `php artisan migrate:rollback` to revert them.

## External Dependencies
- **Laravel Packages**: The project utilizes several Laravel packages. Check `composer.json` for a list of dependencies and ensure they are installed using `composer install`.
- **Frontend Libraries**: Tailwind CSS is used for styling. Refer to `tailwind.config.js` for configuration details.

## Conclusion
Understanding these aspects will help you navigate the codebase effectively and contribute to the project. For further details, refer to the Laravel documentation and the README.md file for project-specific information.