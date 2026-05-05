# AI Agent Instructions for Daily Spot Cafe

## Purpose
Help AI coding agents work effectively in this PHP/MySQL web app by explaining the project structure, conventions, and important implementation details.

## Project overview
- PHP web application served through XAMPP/Apache.
- MySQL database configured in `src/config/database.php`.
- No Composer or modern PHP framework is used.
- The project mixes simple MVC-like controllers/models with page scripts in `admin/` and `public/`.

## Key directories
- `src/config/` - database connection class using `mysqli`.
- `src/controller/` - controller classes for business logic and CRUD operations.
- `src/model/` - plain PHP model classes with prepared statements.
- `admin/` - administration pages for managing categories, orders, inventory, notifications.
- `public/` - authentication pages and public-facing content, including `page/index.php`.
- `uploads/` - stores uploaded images for categories and products.

## Important conventions
- Controllers use relative `require_once` paths, e.g. `require_once '../model/Product.php';`.
- Models use `mysqli` prepared statements with manual input sanitization via `htmlspecialchars(strip_tags(...))`.
- File uploads are stored under `public/uploads/` and referenced using web-relative paths like `uploads/products/...`.
- Database credentials are hard-coded in `src/config/database.php`; local development uses XAMPP.
- `admin/` and `public/` pages are not part of a routing framework; changes should preserve the existing file-based structure unless explicitly refactoring.

## What to assume
- Do not assume a framework such as Laravel, Symfony, or CodeIgniter.
- Do not assume auto-loading; keep explicit `require_once` statements unless adding a safe project-wide refactor.
- The application runs at `http://localhost/daily-spot-cafe/`.

## Common pitfalls
- Search query logic and SQL conditions may need proper parentheses and validation.
- File upload paths are relative; verify `move_uploaded_file()` destination and web path consistency.
- Validate categories before use, as controllers rely on category existence.
- There are no visible test suites or build commands in the repository.

## Best practices for edits
- Keep changes minimal and consistent with the existing structure.
- When fixing bugs, prefer controller/model updates over broad architecture rewrites.
- If adding new features, place them in `src/controller/` and `src/model/` and wire them to the page scripts in `admin/` or `public/`.
- Preserve user-facing directories and static asset organization.

## Recommended next customizations
- `create-skill file-upload-validation` — enforce upload size/type checks and safe storage.
- `create-skill php-crud-review` — help review and improve controller/model CRUD patterns.
- `create-prompt security-check` — identify possible SQL, XSS, and file upload issues in PHP code.
