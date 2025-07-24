# Running Tests with wp-env

This plugin uses WordPress' wp-env for testing. Here's how to run the tests:

## Prerequisites

Make sure you have the following installed:
- [Node.js](https://nodejs.org/) (at least v14)
- [Docker](https://www.docker.com/)
- [npm](https://www.npmjs.com/) or [Yarn](https://yarnpkg.com/)

## Setting up wp-env

If you haven't already installed wp-env globally, you can do so with:

```bash
npm install -g @wordpress/env
```

## Running the tests

1. Start the wp-env environment:

```bash
wp-env start
```

2. Run PHPUnit tests:

```bash
wp-env run tests-cli "cd /var/www/html/wp-content/plugins/family-recipe-book && ./vendor/bin/phpunit"
```

Or run a specific test file:

```bash
wp-env run tests-cli "cd /var/www/html/wp-content/plugins/family-recipe-book && ./vendor/bin/phpunit tests/test-post-types.php"
```

## Stopping the environment

When you're done, you can stop the environment:

```bash
wp-env stop
```

## Troubleshooting

If you encounter issues:

1. Try restarting the environment:
```bash
wp-env stop && wp-env start
```

2. Check Docker is running correctly
3. Make sure the `.wp-env.json` configuration is correct
4. Verify your test files are using the correct format for WordPress tests
