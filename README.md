# Family Recipe Book

A WordPress plugin for managing family recipes with modern best practices. This plugin creates a custom post type for recipes with a locked Gutenberg block template, ensuring consistent recipe formatting while providing a user-friendly interface.

## Features

- Custom "Recipes" post type with appropriate labels, supports, and rewrite rules
- Custom taxonomies for recipe categories and tags
- Locked Gutenberg block template for consistent recipe formatting
- Recipe metadata storage using WordPress standard methods
- Recipe details block for displaying prep time, cook time, servings, etc.
- Schema.org Recipe markup for improved SEO
- Responsive design for both admin and frontend
- Print-friendly recipe styling

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Node.js 14.x or higher (for development)
- Composer (for development)

## Installation

### From GitHub

1. Download the latest release from the [GitHub repository](https://github.com/yourusername/family-recipe-book/releases)
2. Upload the plugin files to the `/wp-content/plugins/family-recipe-book` directory
3. Activate the plugin through the 'Plugins' screen in WordPress

### Manual Installation

1. Clone the repository: `git clone https://github.com/yourusername/family-recipe-book.git`
2. Navigate to the plugin directory: `cd family-recipe-book`
3. Install PHP dependencies: `composer install --no-dev`
4. Install JavaScript dependencies: `npm install`
5. Build the assets: `npm run build`
6. Upload the plugin files to the `/wp-content/plugins/family-recipe-book` directory
7. Activate the plugin through the 'Plugins' screen in WordPress

## Usage

### Creating a Recipe

1. In your WordPress admin, go to "Recipes" > "Add New"
2. Enter a title for your recipe
3. The editor will display a locked template with the following sections:
   - Description
   - Ingredients
   - Instructions
   - Recipe Details
4. Fill in each section with your recipe information
5. Set recipe details (prep time, cook time, servings, etc.) using the Recipe Details block
6. Assign recipe categories and tags as needed
7. Add a featured image to represent your recipe
8. Publish your recipe

### Recipe Details

The Recipe Details block allows you to specify the following information:

- Preparation Time (minutes)
- Cooking Time (minutes)
- Total Time (minutes) - automatically calculated from prep and cook times
- Servings
- Calories (per serving)
- Difficulty (Easy, Medium, Hard)

This information is stored as post meta and is used for both display and schema markup.

### Frontend Display

Recipes are displayed with a clean, responsive layout that includes:

- Recipe title and featured image
- Description
- Ingredients list
- Numbered instructions
- Recipe details (prep time, cook time, servings, etc.)

The layout is optimized for both desktop and mobile viewing, and includes print-friendly styling.

## Development

### Setup Development Environment

1. Clone the repository: `git clone https://github.com/yourusername/family-recipe-book.git`
2. Navigate to the plugin directory: `cd family-recipe-book`
3. Install PHP dependencies: `composer install`
4. Install JavaScript dependencies: `npm install`
5. Start the development build process: `npm start`

### Local Testing Environment

The plugin includes a local WordPress development environment using `@wordpress/env`. This allows you to test the plugin in a real WordPress environment without needing to set up a separate WordPress installation.

#### Requirements for Local Environment

- Docker installed and running
- Node.js 14.x or higher
- npm 7.x or higher

#### Starting the Local Environment

To start the local WordPress environment:

```
npm run env:start
```

This will create a WordPress installation at http://localhost:8888 with the plugin activated. The admin credentials are:

- Username: `admin`
- Password: `password`

#### Managing the Local Environment

- Start the environment: `npm run env:start`
- Stop the environment: `npm run env:stop`
- Destroy the environment: `npm run env:destroy`
- View logs: `npm run env:logs`
- Run WP-CLI commands: `npm run env:cli -- <command>`
  - Example: `npm run env:cli -- plugin list`

#### Combined Development Workflow

To start both the webpack build process and the local environment simultaneously:

```
npm run dev
```

#### Running Tests

To run PHP unit tests in the local environment:

```
npm run test:php
```

### Build for Production

To build the plugin for production:

```
npm run build
```

### Linting

To lint PHP code:

```
composer lint
```

To fix PHP code style issues:

```
composer fix
```

To lint JavaScript code:

```
npm run lint:js
```

To lint CSS:

```
npm run lint:css
```

### Testing

To run PHP unit tests:

```
composer test
```

To run PHP unit tests with coverage report:

```
composer test:coverage
```

To run PHP unit tests in the local WordPress environment:

```
npm run test:php
```

## Directory Structure

```
family-recipe-book/
├── assets/                  # Compiled assets
│   ├── css/                 # CSS files
│   │   ├── admin.css        # Admin styles
│   │   ├── public.css       # Frontend styles
│   │   ├── print.css        # Print styles
│   │   └── sharing.css      # Sharing styles
│   └── js/                  # JavaScript files
│       ├── admin.js         # Admin scripts
│       ├── public.js        # Frontend scripts
│       ├── print.js         # Print scripts
│       └── sharing.js       # Sharing scripts
├── build/                   # Built block files (generated)
├── includes/                # PHP classes
│   ├── class-post-types.php # Recipe post type
│   ├── class-blocks.php     # Block registration
│   ├── class-schema.php     # Schema.org markup
│   ├── class-template-locking.php # Template locking
│   ├── class-settings.php   # Admin settings
│   ├── class-print.php      # Print functionality
│   └── class-sharing.php    # Social sharing
├── languages/               # Translation files
├── src/                     # Source files for blocks
│   └── blocks/              # Block source files
│       ├── recipe-details/  # Recipe details block
│       ├── recipe-ingredients/ # Recipe ingredients block
│       └── recipe-instructions/ # Recipe instructions block
├── tests/                   # Test files
│   ├── bootstrap.php        # Test bootstrap
│   └── test-*.php           # Test cases
├── vendor/                  # Composer dependencies (generated)
├── node_modules/            # npm dependencies (generated)
├── .gitignore               # Git ignore file
├── .wp-env.json             # WordPress environment config
├── composer.json            # Composer configuration
├── package.json             # npm configuration
├── phpunit.xml.dist         # PHPUnit configuration
├── family-recipe-book.php   # Main plugin file
├── CHANGELOG.md             # Development changelog
├── SUMMARY.md               # Development summary
└── README.md                # This file
```

## Hooks and Filters

The plugin provides several hooks and filters to extend its functionality:

### Filters

- `dvnl_recipe_schema_data`: Filter the schema.org data before output
- `dvnl_recipe_template`: Filter the block template for recipes
- `dvnl_recipe_meta_fields`: Filter the meta fields for recipes

### Actions

- `dvnl_recipe_before_details`: Action before recipe details are displayed
- `dvnl_recipe_after_details`: Action after recipe details are displayed

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- [WordPress](https://wordpress.org/)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Schema.org Recipe Schema](https://schema.org/Recipe)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request

## Support

For support, please open an issue on the [GitHub repository](https://github.com/yourusername/family-recipe-book/issues).