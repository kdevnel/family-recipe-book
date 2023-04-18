# Family Recipe Book Plugin for WordPress

A plugin for allowing you to store family recipes and display them on your WordPress website.
Technology

Built using the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) as a foundation.

# Development Installation
* Add the plugin folder at `/family-recipe-book` to your WordPress site's `/wp-content/plugins` folder
* Activate the plugin

# Development

## Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `family-recipe-book/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `family-recipe-book/admin` is for all admin-specific functionality
* `family-recipe-book/public` is for all public-facing functionality

Note that previous versions of the Boilerplate did not include `family_recipe_book_Loader` but this class is used to register all filters and actions with WordPress.

## Recommended Tools

### i18n Tools

The WordPress Plugin Boilerplate uses a variable to store the text domain used when internationalizing strings throughout the Boilerplate. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.