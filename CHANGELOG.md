# Changelog

All notable changes to this library will be documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-07-02
### Added
- Support for Laravel 11, 12 and 13.
- Support for PHP 8.4 and 8.5.
- `declare(strict_types=1)` across the package source and tests.
### Changed
- The published migration is now an anonymous class, matching current Laravel conventions.
- `phpunit.xml` and the test suite were migrated to the PHPUnit 11/12 schema and attribute syntax.
- CI now runs against a PHP 8.2–8.5 x Laravel 11/12/13 matrix.
### Removed
- Support for PHP 8.1.

## [1.1.0] - 2023-04-29
### Added
- `Scopes` - Add `today` and `beforeToday` scopes
### Changed
- `Comments` - Change the logic to auto comment approved
- `Readme` - Update README.md
- `Tests` - Update tests

## [1.0.0] - 2023-04-28

- Initial release

[2.0.0]: https://github.com/nazaryuzhyn/laravel-comments/releases/tag/v2.0.0
[1.1.0]: https://github.com/nazaryuzhyn/laravel-comments/releases/tag/v1.1.0
[1.0.0]: https://github.com/nazaryuzhyn/laravel-comments/releases/tag/v1.0.0
