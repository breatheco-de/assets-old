const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');

var BUILD_DIR = path.resolve(__dirname, 'public-dev');
module.exports = merge(common, {
    output: {
        path: BUILD_DIR,
        filename: '[name].js'
    },
    mode: 'development',
    devtool: 'source-map',
    devServer: {
        contentBase:  './dist',
    }
})