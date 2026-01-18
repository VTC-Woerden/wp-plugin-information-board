# VTC Informatiebord Plugin

A professional WordPress plugin for managing information boards and sponsors.

## Features

- Custom information board page with custom template
- Sponsor custom post type with full CRUD operations
- Sponsor taxonomy (Normal/Hoofd sponsors)
- Media upload for sponsor logos
- Modern OOP architecture

## File Structure

```
wp-plugin-information-board/
├── plugin.php                          # Main plugin file
├── information-board-template.php      # Custom page template
├── includes/
│   └── class-sponsor-post-type.php    # Sponsor post type class
├── assets/
│   └── js/
│       └── sponsor-admin.js           # Admin JavaScript
└── README.md
```

## Architecture

The plugin follows WordPress best practices with:

- **Singleton Pattern**: Main plugin and sponsor classes use singleton pattern
- **Class-based Structure**: All functionality organized in classes
- **Separation of Concerns**: Each class handles specific functionality
- **Security**: Nonce verification, capability checks, input sanitization
- **Internationalization**: Translation-ready with text domain
- **Constants**: Plugin paths and URLs defined as constants

## Usage

### Creating a Sponsor

1. Go to **Sponsors** in WordPress admin
2. Click **Nieuwe toevoegen**
3. Enter sponsor name
4. Select sponsor type (Normale Sponsor or Hoofd Sponsor)
5. Upload logo via media library
6. Publish

### Programmatic Access

```php
// Get all sponsors
$sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type();

// Get only main sponsors
$main_sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type('hoofd-sponsor');

// Get sponsor logo URL
$logo_url = VTC_Sponsor_Post_Type::get_logo_url($post_id);
```

## Development

The plugin uses modern WordPress development practices:

- OOP with classes and namespacing
- WordPress Coding Standards
- Singleton pattern for class instances
- Proper hook management
- Clean separation of admin and frontend code

## Requirements

- WordPress 5.0+
- PHP 7.4+