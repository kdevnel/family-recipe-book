/**
 * External dependencies
 */
const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

// Entry points
const entry = {
    blocks: './src/blocks/index.js',
};

// Output
const output = {
    path: path.resolve(process.cwd(), 'build'),
    filename: '[name].js',
    chunkFilename: '[name].js',
};

// Rules
const rules = [
    ...defaultConfig.module.rules,
    {
        test: /\.css$/,
        use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            {
                loader: 'postcss-loader',
                options: {
                    postcssOptions: {
                        plugins: [
                            require('autoprefixer'),
                            require('cssnano')({
                                preset: 'default',
                            }),
                        ],
                    },
                },
            },
        ],
    },
];

// Plugins
const plugins = [
    ...defaultConfig.plugins,
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({
        filename: '[name].css',
    }),
    new DependencyExtractionWebpackPlugin({
        injectPolyfill: true,
    }),
];

// Optimization
const optimization = {
    ...defaultConfig.optimization,
    minimize: process.env.NODE_ENV === 'production',
    minimizer: [
        new TerserPlugin({
            terserOptions: {
                parse: {
                    ecma: 8,
                },
                compress: {
                    ecma: 5,
                    warnings: false,
                    comparisons: false,
                    inline: 2,
                },
                mangle: {
                    safari10: true,
                },
                output: {
                    ecma: 5,
                    comments: false,
                    ascii_only: true,
                },
            },
            extractComments: false,
        }),
        new CssMinimizerPlugin(),
    ],
};

// Export configuration
module.exports = {
    ...defaultConfig,
    entry,
    output,
    module: {
        ...defaultConfig.module,
        rules,
    },
    plugins,
    optimization,
};