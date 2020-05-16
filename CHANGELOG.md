# Changelog

All notable changes to `ntavelis/mercure-php` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [Unreleased

[0.4.0] - 2020-05-16

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
