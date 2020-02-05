# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- .gitattributes file
- \BristolSU\Support\User\UserRepository::getById method for retrieving a user by ID
- \BristolSU\Support\User\UserRepository::setRememberToken to set a users remember token

## [2.0.1] - (05/02/2020)

### Added
- Integration to use a user as a notifiable in the laravel framework, for mail  

## [2.0] - (04/02/2020)

### Added
- doctrine/dbal dependency for editing migrations
- Logout function for UserAuthentication
- UserRepository::getWhereEmail function to retrieve a user via email
- UserRepository::getFromControlId function to retrieve a user via control ID
- UserRepository::getFromRememberToken function to get by remember token
- Functions to get the control user from the database user

### Removed
- Forename, surname, email and student ID columns from database user model.
- UserRepository::getWhereIdentity function

## [1.1] - (30/01/2020)

### Changed
- Bumped the control dependency to version 1 (stable)
- Allow Module Instance Setting values to be null
- Refresh the module builder instance in the factory

### Added
- Laravel Session Authentication

### Removed
- Laravel Web Authentication



## [1.0.1] - (23/01/2020)

### Changed
- Generate a new module builder each time the module factory is used
- Make module instance setting values nullable 
- Connection ID attribute is now fillable for a module instance service
- Prefer stable dependencies

## [1.0] - (17/01/2020)

### Added
- Initial Release

[Unreleased]: https://github.com/bristol-su/support/compare/v2.0.1...HEAD
[2.0.1]: https://github.com/bristol-su/support/compare/v2.0...v2.0.1
[2.0]: https://github.com/bristol-su/support/compare/v1.1...v2.0
[1.1]: https://github.com/bristol-su/support/compare/v1.0.1...v1.1
[1.0.1]: https://github.com/bristol-su/support/compare/v1.0...v1.0.1
[1.0]: https://github.com/bristol-su/support/releases/tag/v1.0
