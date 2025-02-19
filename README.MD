# Family Recipe Book Plugin for WordPress

## Technologies

Built using:
- [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- Composer for dependency management (PHPCS and WPCS)
- WordPress Coding Standards (via included phpcs.xml)

## Development Installation

1. Clone repository:
```bash
git clone https://github.com/kdevnel/family-recipe-book.git

cd family-recipe-book
```

2. Install dependencies:
```bash
# Composer dependencies first
composer install

# Node dependencies next
cd dvnl-family-recipe-book

# If using nvm - switch to correct node version
nvm use

npm install
```

3. Build assets:
```bash
# Build only
npm run build

# Watch for changes and build them
npm run start
```

4. Start development site
```bash
# Run with npm to ensure we use the bundled version
npm run wp-env
```

## Documentation

Project documentation can be found in `/docs/`

## Development

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `family-recipe-book/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `family-recipe-book/admin` is for all admin-specific functionality
* `family-recipe-book/public` is for all public-facing functionality

Note that previous versions of the Boilerplate did not include `family_recipe_book_Loader` but this class is used to register all filters and actions with WordPress.

### Post Types
To register a new post type:
* register a hook in `family-recipe-book/includes/class-dvnl-family-recipe-book.php`
* Create a new method defining the fields for your post type in `dvnl-family-recipe-book/includes/class-dvnl-family-recipe-book-post-types.php`

### Metaboxes and custom field
To register a metabox:
* define your metabox details in the `create_recipe_metaboxes` method as a new array.
* create a new template in `admin/partials` and define the path in the initial definition method above.
* To register the save method, add a new array to `$field_args` in the `save_recipe_metaboxes` method where you can define your fields and nonce.

## Recommended Tools

### i18n Tools

The WordPress Plugin Boilerplate uses a variable to store the text domain used when internationalizing strings throughout the Boilerplate. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.