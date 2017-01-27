# Changelog

All Notable changes to `larablog` will be documented in this file.

## [Unreleased]

### Added
- Replaced references to 'admin' middleware with middleware referenced in config
- Replaced calls to function 'isUserAdmin()' with reference to function specified in middleware
- Replaced tinymce editor with CKeditor because the tinymce was starting to annoy me

### Fixed
- Simplified controller code
- Removed function and calls to setLanguage(), replaced with my middleware
- Put missing function back in

## v1.1.3 2017-01-16

### Added
- Translations for English and French
- Function in Controller to pull language from session and set config accordingly
- Added config setting to disable flash messages
- Updated archives so it translates the month

### Changes
- Views to use translations instead of hardcoded data
- Dates use strftime() to translate dates instead of date()

