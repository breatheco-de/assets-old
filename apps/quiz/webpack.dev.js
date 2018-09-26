const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');
const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: '[name].js'
    },
    mode: 'development',
    devtool: 'source-map',
    devServer: {
        contentBase:  './dist',
    },
    plugins: [
        new webpack.HotModuleReplacementPlugin(),
        new Dotenv({
            path: './.env.dev',
            systemvars: true
        }),
    ]
})