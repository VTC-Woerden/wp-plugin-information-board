# VTC Informatiebord Plugin

A professional WordPress plugin for managing information boards and sponsors.

## Features

- Custom information board page with custom template
- Sponsor custom post type with full CRUD operations
- Sponsor taxonomy (Normal/Hoofd sponsors)
- Featured image support for sponsor logos
- Full-screen sponsorboard display page
- Sponsor shortcodes for embedding sponsors anywhere
- Modern OOP architecture

## File Structure

```
wp-plugin-information-board/
├── plugin.php                          # Main plugin file
├── information-board-template.php      # Custom page template
├── sponsorboard-template.php           # Full-screen sponsor display
├── includes/
│   ├── class-sponsor-post-type.php    # Sponsor post type class
│   └── class-sponsor-shortcodes.php   # Sponsor shortcodes
├── assets/
│   ├── css/
│   │   └── sponsors.css               # Shortcode styles
│   └── js/
│       └── sponsors-rotating.js       # Rotating shortcode JS
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
5. Set featured image as sponsor logo
6. Publish

### Sponsorboard Page

A full-screen digital sponsor board is automatically created at `/sponsorboard`. Features:
- Clean, distraction-free display
- 6 sponsors per row
- Responsive grid layout
- All sponsors displayed with equal sizing
- Perfect for TV/monitor displays

### Shortcodes

#### Grid Shortcode (All Sponsors Visible)

Display all sponsors in a grid layout:

```
[sponsors_grid]
[sponsors_grid columns="6"]
[sponsors_grid columns="4" type="hoofd-sponsor"]
```

**Parameters:**
- `columns` - Number of sponsors per row (default: 6)
- `type` - Filter by type: `normale-sponsor`, `hoofd-sponsor`, or empty for all

**Examples:**
```
[sponsors_grid]                                    # Show all sponsors, 6 per row
[sponsors_grid columns="4"]                        # Show all sponsors, 4 per row
[sponsors_grid type="hoofd-sponsor"]               # Show only main sponsors
[sponsors_grid columns="3" type="normale-sponsor"] # Show normal sponsors, 3 per row
```

#### Rotating Shortcode (One Row at a Time)

Display sponsors one row at a time with automatic rotation:

```
[sponsors_rotating]
[sponsors_rotating per_row="6" interval="5"]
[sponsors_rotating per_row="4" interval="10" type="normale-sponsor"]
```

**Parameters:**
- `per_row` - Number of sponsors per row (default: 6)
- `interval` - Seconds between rotations (default: 5)
- `type` - Filter by type: `normale-sponsor`, `hoofd-sponsor`, or empty for all

**Examples:**
```
[sponsors_rotating]                                      # Rotate all sponsors, 6 per row, every 5 seconds
[sponsors_rotating interval="10"]                        # Rotate every 10 seconds
[sponsors_rotating per_row="4" interval="8"]            # 4 per row, rotate every 8 seconds
[sponsors_rotating type="hoofd-sponsor" interval="15"]  # Only main sponsors, rotate every 15 seconds
```

**Features:**
- Smooth fade transitions between rows
- Automatically groups sponsors into rows
- Responsive design
- Only loads when shortcode is present

### Programmatic Access

```php
// Get all sponsors
$sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type();

// Get only main sponsors
$main_sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type('hoofd-sponsor');

// Get sponsor logo URL
$logo_url = VTC_Sponsor_Post_Type::get_logo_url($post_id);

// Get logo in specific size
$logo_thumb = VTC_Sponsor_Post_Type::get_logo_url($post_id, 'medium');
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