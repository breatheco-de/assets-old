const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
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