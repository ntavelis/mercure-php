# Changelog

All notable changes to `ntavelis/mercure-php` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [Unreleased]

## [2.0.0] - 2026-03-29

### Breaking Changes
- Minimum PHP version raised to 8.3
- `PublisherTokenProvider` and `SubscriberTokenProvider` now throw `InvalidSecretKeyLengthException` if the secret key is shorter than 32 characters (256 bits)

### Added
- `InvalidSecretKeyLengthException` to enforce HMAC-SHA256 minimum key length requirement
- PHP 8.3 and 8.5 added to CI test matrix (unit and functional)

### Changed
- Bumped `lcobucci/jwt` from `^4.1.5` to `^5.6`
- Bumped `nyholm/psr7` from `^1.4.1` to `^1.8`
- Bumped `phpstan/phpstan` from `^0.12` to `^1.0`
- Bumped `phpunit/phpunit` from `^8.0` to `^11.0`
- Bumped `symfony/http-client` from `^5.3` to `^6.0`
- All properties now use typed declarations
- PHPUnit test annotations migrated from `@test` docblocks to `#[Test]` attributes
- PHPUnit XML configuration migrated to new schema

### Fixed
- Missing `declare(strict_types=1)` in `TokenProviderInterface`
- `$topics` property in `NotificationBuilder` now explicitly initialized to empty array

## [1.1.0] - 2021-11-13

### Changed
- Changed the way we handle private notifications to comply with the latest changes in mercure hub.
 
[1.0.1] - 2021-11-13

### Added
- Updating dev dependencies
- Cleaner documentation
- Enabled functional tests in CI

[1.0.0] - 2021-11-02

### Added
- Minimum requirement php 7.4
- Updating dependencies
- Refactored code to support newer version of JWT library

- [0.4.0] - 2020-05-16

### Added
- Added a ConfigStamp class, that accepts specific config values for the notifications
- Added the ability to pass the config stamp through the fluent builders

### Changed
- Changed the NotificationInterface no longer extends jsonSerializable, instead it exposes a toArray method
- Interface docblock, now it contains proper exception in docblock 
- Updated the README.md

## [0.3.2] - 2020-05-09

### Changed
- Changed .gitattributes file, to correctly reflect new code

## [0.3.1] - 2020-05-09

### Added
- Added builders documentation

### Changed

- Updated the README.md

## [0.3.0] - 2020-05-09

### Changed
- Changed typehint to nullable string \Ntavelis\Mercure\QueryBuilder::buildQueryString

### Added
- Added builder for publisher
- Added builder for notification messages (public, private)

## [0.2.5] - 2020-05-06

### Added
- Added functional tests to the github actions

### Changed
- Readme modifications

## [0.2.4] - 2020-05-06

### Changed
- Move phpstan to require-dev section

## [0.2.3] - 2020-05-06

### Added
- Add files to export-ignore

## [0.2.2] - 2020-05-06

### Added
- Add composer.lock to gitignore

## [0.2.1] - 2020-05-06

### Added
- Added .github folder as export-ignore

## [0.2.0] - 2020-05-06

### Added
- Switched to github actions
- Added static analysis (phpstan)

### Fixed
- Phpstan violations

## [0.1.2] - 2019-09-01

### Fixed
- Added docker folder to export-ignore

## [0.1.1] - 2019-08-31

### Fixed
- Publisher token provider test name
- Updated readme

## [0.1.0] - 2019-08-26
### Added
- First package version

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
