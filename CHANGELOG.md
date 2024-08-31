# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added

- Initial release of the Semantic MVC Framework.

## [1.0.0] - 2024-08-31

### Added

- **2024-08-31:** Initial release of the Semantic MVC Framework.
  - Basic MVC structure with `FastRoute` for routing.
  - `View` class for rendering HTML views.
  - Basic `Router` class for handling routes.
  - `SelectSql` and `otherSql` functions for database interaction.
  - Example controllers: `LoginController`, `DashboardController`.
  - Example views for login and dashboard.

### Changed

- **2024-08-31:** Set up Composer for dependency management.
- **2024-08-31:** Configured XAMPP for local development.

### Fixed

- **2024-08-31:** Fixed routing issues to correctly handle 404 and 405 HTTP responses.
- **2024-08-31:** Ensured views are served from the `public` directory while HTML views are stored in the `views` directory.

## [1.1.0] - 2024-08-31

### Added

- **2024-08-31:** Added support for a custom license (Proprietary).
- **2024-08-31:** Integrated Packagist for package distribution.

### Changed

- **2024-08-31:** Updated documentation for installation and usage.
- **2024-08-31:** Improved `.htaccess` configuration for better routing.

### Fixed

- **2024-08-31:** Resolved issues with local development setup in XAMPP.
- **2024-08-31:** Fixed bugs related to view rendering and AJAX communication.

## [1.2.0] - 2024-08-31

### Added

- **2024-08-31:** Added example usage of AJAX for front-end communication.
- **2024-08-31:** Introduced basic authentication for routes in the MVC application.

### Changed

- **2024-08-31:** Refined layout management to support logged-in dashboard views.
- **2024-08-31:** Enhanced error handling in `Router` class.

### Fixed

- **2024-08-31:** Fixed issues with dynamic route handling and view loading.

## [1.2.1] - 2024-08-31

### Fixed

- **2024-08-31:** Fixed minor bugs related to view paths and routing inconsistencies.

---

**Note:** This CHANGELOG follows the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) principles and adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
