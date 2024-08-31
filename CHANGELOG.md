# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added

- **Session Management API:**
  - Added `/api/refresh-session` route to allow front-end applications to refresh the session.
  - Added `Auth::refreshSession()` method to update session activity timestamp without creating a new session.
  - Introduced `getRemainingSessionTime()` method to check the remaining time before session expiration.
- **Configuration Settings:**
  - Added support for configuring session cookie parameters (`SESSION_COOKIE_LIFETIME`, `SESSION_COOKIE_SECURE`, etc.) in `config.php`.
  - Added `SESSION_TIMEOUT` and session management configuration variables to `config.php`.

### Changed

- **Session Management:**
  - Refactored `Auth` class to separate session creation and refreshing logic.
  - `Auth::startSession()` now initializes the session and checks for expiration.
  - `Auth::refreshSession()` updates the session's `LAST_ACTIVITY` timestamp.
- **Timezone Handling:**
  - Set timezone to 'Europe/Athens' globally using `date_default_timezone_set()`.

### Fixed

- **Session Expiration Handling:**

  - Fixed issues with session expiration logic to ensure sessions are properly logged out when expired.
  - Improved session handling to prevent automatic session refresh after expiration.

- **Routing and API:**
  - Fixed issues related to route paths and URL handling in the `Router` class.
  - Enhanced API routes to handle session management and authentication checks more effectively.

## [1.0.0] - 2024-08-31

### Added

- **Initial Release:**
  - Basic MVC structure with `FastRoute` for routing.
  - `View` class for rendering HTML views.
  - Basic `Router` class for handling routes.
  - `SelectSql` and `otherSql` functions for database interaction.
  - Example controllers: `AuthController`, `DashboardController`.
  - Example views for login and dashboard.

### Changed

- **Dependency Management:**
  - Set up Composer for dependency management.
- **Development Environment:**
  - Configured XAMPP for local development.

### Fixed

- **Routing:**
  - Fixed routing issues to correctly handle 404 and 405 HTTP responses.
- **View Handling:**
  - Ensured views are served from the `public` directory while HTML views are stored in the `views` directory.

## [1.1.0] - 2024-08-31

### Added

- **Licensing:**
  - Added support for a custom license (Proprietary).
- **Package Management:**
  - Integrated Packagist for package distribution.

### Changed

- **Documentation:**
  - Updated documentation for installation and usage.
- **Configuration:**
  - Improved `.htaccess` configuration for better routing.

### Fixed

- **Local Development:**

  - Resolved issues with local development setup in XAMPP.

- **View Rendering:**
  - Fixed bugs related to view rendering and AJAX communication.

## [1.2.0] - 2024-08-31

### Added

- **AJAX Integration:**
  - Added example usage of AJAX for front-end communication with the backend.
- **Authentication:**
  - Introduced basic authentication for routes in the MVC application.

### Changed

- **Layout Management:**
  - Refined layout management to support logged-in dashboard views.
- **Error Handling:**
  - Enhanced error handling in `Router` class.

### Fixed

- **Dynamic Routing:**
  - Fixed issues with dynamic route handling and view loading.

## [1.2.1] - 2024-08-31

### Fixed

- **View Paths:**
  - Fixed minor bugs related to view paths and routing inconsistencies.

## [1.2.2] - 2024-08-31

### Fixed

- **Route Paths:**
  - Fixed route paths and updated auth middleware functionality.
- **Auth Middleware:**
  - Improved middleware to handle session expiration and login redirection properly.

---

**Note:** This CHANGELOG follows the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) principles and adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
