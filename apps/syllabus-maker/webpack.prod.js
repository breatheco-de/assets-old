const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');
const webpack = require('webpack');

var BUILD_DIR = path.resolve(__dirname, 'public');

module.exports = merge(common, {
    output: {
        path: BUILD_DIR,
        filename: '[name].js'
    },
    mode: 'production',
    devtool: "source-map",
})