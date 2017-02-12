# Changelog

All Notable changes to `larablog` will be documented in this file.

## Unreleased 


## v.1.2.0-beta.2 [2017-02-12]

### Added
- Updated and improved tests
- Added RichCard with JSON data
- Added image to display in rich card, if available

### Fixed
- Fixed error where controller was referencing non-existent config file
- Fixed error in date and time display
- Fixed error where comment form was showing to users who were not logged in

## v1.2.0-beta.1 [2017-01-31]

### Added
- Replaced references to 'admin' middleware with middleware referenced in config
- Replaced calls to function 'isUserAdmin()' with reference to function specified in middleware
- Replaced tinymce editor with CKeditor because the tinymce was starting to annoy me
- Removed references to Laravel's form package to simplify installation and reduce dependencies
- Added ability to reply to comments
- Added nested comment display
- Added ability to delete your own comments

### Fixed
- Simplified controller code
- Removed function and calls to setLanguage(), replaced with my middleware
- Put missing function back in
- Fixed error in date and time display
- Fixed error where comment form was showing to users who were not logged in

## v1.1.3 2017-01-16

### Added
- Translations for English and French
- Function in Controller to pull language from session and set config accordingly
- Added config setting to disable flash messages
- Updated archives so it translates the month

### Changes
- Views to use translations instead of hardcoded data
- Dates use strftime() to translate dates instead of date()

